<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Trait;

use DiamondStrider1\SerDe\Interface\Serialize;
use DiamondStrider1\SerDe\Reflect\SerDeClass;
use LogicException;

trait SerializeTrait
{
	public function serialize(): array
	{
		$class = SerDeClass::get(self::class);
		$data = [];
		foreach ($class->getFields() as $field) {
			$value = $field->getProperty()->setAccessible(true);
			$value = $field->getProperty()->getValue($this);
			$transformer = $field->getTransformer();
			if (null !== $transformer) {
				$transformed = $transformer->serialize($value);
				$data[$field->getName()] = $transformed;
			} elseif ($field->isPrimitive()) {
				$data[$field->getName()] = $value;
			} elseif ($value instanceof Serialize) {
				$transformed = $value->serialize();
				$data[$field->getName()] = $transformed;
			} else {
				throw new LogicException('Cannot serialize property of class because its type is not a primitive or an instance of Serialize and it has no Transformer attribute attached: '.self::class.'::'.$field->getProperty()->getName());
			}
		}

		return $data;
	}
}
