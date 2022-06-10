<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Reflect;

use DiamondStrider1\SerDe\Attribute\Field;
use DiamondStrider1\SerDe\Attribute\Transformer\Transformer;
use DiamondStrider1\SerDe\Interface\Deserialize;
use DiamondStrider1\SerDe\Interface\Serialize;
use InvalidArgumentException;
use LogicException;
use ReflectionAttribute;
use ReflectionClass;

/**
 * @phpstan-template T of (Serialize|Deserialize)
 */
final class SerDeClass
{
	/**
	 * @var array<string, self>
	 * @phpstan-var array<class-string<Serialize|Deserialize>, self<Serialize|Deserialize>>
	 */
	private static array $cache = [];

	/**
	 * @phpstan-template U of (Serialize|Deserialize)
	 * @phpstan-param class-string<U> $class
	 * @phpstan-return self<U>
	 */
	public static function get(string $class): self
	{
		if (isset(self::$cache[$class])) {
			// @phpstan-ignore-next-line
			return self::$cache[$class];
		}
		if (!class_exists($class)) {
			throw new InvalidArgumentException('$class is not a class!');
		}
		if (
			!is_a($class, Serialize::class, true) &&
			!is_a($class, Deserialize::class, true)
		) {
			throw new InvalidArgumentException('$class does not implement Serialize or Deserialize!');
		}

		// @phpstan-ignore-next-line
		$rClass = new ReflectionClass($class);
		$rProps = $rClass->getProperties();
		$fields = [];
		foreach ($rProps as $rProp) {
			$field = $rProp->getAttributes(Field::class)[0] ?? null;
			if (null === $field) {
				continue;
			}
			$transformers = $rProp->getAttributes(
				Transformer::class, ReflectionAttribute::IS_INSTANCEOF
			);
			if (count($transformers) > 1) {
				throw new LogicException("There should only be one Transformer per property: $class::{$rProp->getName()}");
			}
			$transformer = $transformers[0] ?? null;
			$fields[] = new SerDeField(
				$field->newInstance(),
				$transformer?->newInstance(),
				$rProp
			);
		}

		// @phpstan-ignore-next-line
		return self::$cache[$class] = new self($fields);
	}

	/**
	 * @param SerDeField[] $fields
	 */
	private function __construct(
		private array $fields,
	) {
	}

	/**
	 * @return SerDeField[]
	 */
	public function getFields(): array
	{
		return $this->fields;
	}
}
