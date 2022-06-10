<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Attribute\Transformer;

use DiamondStrider1\SerDe\Parse\Error\MismatchTypeError;
use DiamondStrider1\SerDe\Parse\Error\ParseError;
use DiamondStrider1\SerDe\Reflect\Type;
use InvalidArgumentException;

trait ArrayTransformerTrait
{
	public function serialize(mixed $value): mixed
	{
		if (!is_array($value)) {
			throw new InvalidArgumentException('$value is not an array!');
		}
		$data = [];
		foreach ($value as $k => $v) {
			$data[$k] = $this->serializeElement($v);
		}

		return $data;
	}

	/**
	 * @return TransformedValue|ParseError|array<int, ParseError>
	 */
	public function deserialize(mixed $value, string $errorKey): TransformedValue|ParseError|array
	{
		if (!is_array($value)) {
			return new MismatchTypeError($errorKey, 'array', Type::getName($value));
		}
		$transformed = [];
		$errors = [];
		foreach ($value as $k => $v) {
			$deserialized = $this->deserializeElement("$errorKey.$k", $v);
			if ($deserialized instanceof ParseError) {
				$errors[] = $deserialized;
			} else {
				$transformed[$k] = $deserialized->get();
			}
		}
		if (count($errors) > 0) {
			return $errors;
		}

		return new TransformedValue($transformed);
	}

	abstract public function serializeElement(mixed $value): mixed;

	abstract public function deserializeElement(string $key, mixed $value): TransformedValue|ParseError;
}
