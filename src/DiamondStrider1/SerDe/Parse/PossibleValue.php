<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Parse;

/**
 * @phpstan-template T
 */
final class PossibleValue
{
	/** @phpstan-var T $value */
	private mixed $value;
	private bool $valueExists;

	private function __construct(
	) {
	}

	/**
	 * @phpstan-template U
	 * @phpstan-param U $value
	 * @phpstan-return self<U>
	 */
	public static function value(mixed $value): self
	{
		$self = new self();
		$self->value = $value;
		$self->valueExists = true;

		return $self;
	}

	/**
	 * @phpstan-return self<void>
	 */
	public static function empty(): self
	{
		$self = new self();
		$self->valueExists = false;

		return $self;
	}

	/**
	 * @phpstan-return T
	 */
	public function getValue(): mixed
	{
		if (!$this->valueExists) {
			throw new EmptyPossibleValueException();
		}

		return $this->value;
	}
}
