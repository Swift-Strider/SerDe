<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Trait;

use DiamondStrider1\SerDe\Attribute\Transformer\TransformedValue;
use DiamondStrider1\SerDe\Parse\Error\MismatchTypeError;
use DiamondStrider1\SerDe\Parse\Error\MissingPropertyError;
use DiamondStrider1\SerDe\Parse\Error\ParseError;
use DiamondStrider1\SerDe\Parse\ParseErrorsEncounteredException;
use DiamondStrider1\SerDe\Parse\Parser;
use DiamondStrider1\SerDe\Reflect\SerDeClass;
use DiamondStrider1\SerDe\Reflect\Type;
use LogicException;
use ReflectionClass;

trait DeserializeTrait
{
	public static function deserialize(Parser|array $data): self
	{
		if (!$data instanceof Parser) {
			$data = new Parser($data);
		}
		$class = SerDeClass::get(self::class);
		$self = (new ReflectionClass(self::class))->newInstanceWithoutConstructor();
		foreach ($class->getFields() as $field) {
			$value = $data->getRawValue($field->getName());
			$transformer = $field->getTransformer();
			if (null !== $transformer) {
				$transformed = $transformer->deserialize(
					$value, $data->getQualifiedKeyName($field->getName())
				);
				if ($transformed instanceof TransformedValue) {
					$field->getProperty()->setAccessible(true);
					$field->getProperty()->setValue($self, $transformed->get());
				} elseif (
					$transformed instanceof MissingPropertyError &&
					$field->getType()->allowsNull()
				) {
					$field->getProperty()->setAccessible(true);
					$field->getProperty()->setValue($self, null);
				} else {
					if ($transformed instanceof ParseError) {
						$data->getErrors()->reportError($transformed);
					} else {
						foreach ($transformed as $error) {
							$data->getErrors()->reportError($error);
						}
					}
				}
			} elseif ($field->isPrimitive()) {
				$typeName = $field->getType()->getName();
				$allowsNull = $field->getType()->allowsNull();
				$valid = Type::check($typeName, $value);
				if ($valid || (null === $value && $allowsNull)) {
					$field->getProperty()->setAccessible(true);
					$field->getProperty()->setValue($self, $value);
				} else {
					$data->getErrors()->reportError(new MismatchTypeError(
						key: $field->getName(),
						expectedType: $typeName.($allowsNull ? ' or null' : ''),
						gotType: Type::getName($value),
					));
				}
			} elseif (null !== ($type = $field->getDeserializeClass())) {
				$transformed = $type::deserialize($data->traverse($field->getName()));
				$field->getProperty()->setAccessible(true);
				$field->getProperty()->setValue($self, $transformed);
			} else {
				throw new LogicException('Cannot deserialize property of class because its type is not a primitive or an instance of Deserialize and it has no Transformer attribute attached: '.self::class.'::'.$field->getProperty()->getName());
			}
		}

		$errors = $data->getErrors();
		if ($errors->count() > 0) {
			throw new ParseErrorsEncounteredException($errors);
		}

		return $self;
	}
}
