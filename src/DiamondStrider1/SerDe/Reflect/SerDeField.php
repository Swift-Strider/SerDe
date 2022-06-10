<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Reflect;

use DiamondStrider1\SerDe\Attribute\Field;
use DiamondStrider1\SerDe\Attribute\Transformer\Transformer;
use DiamondStrider1\SerDe\Interface\Deserialize;
use DiamondStrider1\SerDe\Interface\Serialize;
use LogicException;
use ReflectionNamedType;
use ReflectionProperty;

final class SerDeField
{
	private string $name;
	private bool $isPrimitive;
	private ReflectionNamedType $type;
	/** @phpstan-var class-string<Serialize> */
	private string $serializeClass;
	/** @phpstan-var class-string<Deserialize> */
	private string $deserializeClass;

	public function __construct(
		private Field $field,
		private ?Transformer $transformer,
		private ReflectionProperty $property,
	) {
		$this->name = $field->name ?? $property->getName();
		$type = $this->property->getType();
		if (!$type instanceof ReflectionNamedType) {
			throw new LogicException('Properties with the Field attribute must have a typed with a single type!');
		}

		$this->type = $type;
		if ($type->isBuiltin()) {
			$this->isPrimitive = true;
		} elseif (class_exists($class = $type->getName())) {
			$this->isPrimitive = false;
			if (is_a($class, Serialize::class, true)) {
				$this->serializeClass = $class;
			}
			if (is_a($class, Deserialize::class, true)) {
				$this->deserializeClass = $class;
			}
		} else {
			$this->isPrimitive = false;
		}
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function isPrimitive(): bool
	{
		return $this->isPrimitive;
	}

	public function getType(): ReflectionNamedType
	{
		return $this->type;
	}

	public function getField(): Field
	{
		return $this->field;
	}

	public function getTransformer(): ?Transformer
	{
		return $this->transformer;
	}

	public function getProperty(): ReflectionProperty
	{
		return $this->property;
	}

	/**
	 * @phpstan-return class-string<Serialize>|null
	 */
	public function getSerializeClass(): ?string
	{
		return $this->serializeClass ?? null;
	}

	/**
	 * @phpstan-return class-string<Deserialize>|null
	 */
	public function getDeserializeClass(): ?string
	{
		return $this->deserializeClass ?? null;
	}
}
