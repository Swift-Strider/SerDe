<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Parse;

use DiamondStrider1\SerDe\Parse\Error\MismatchTypeError;
use DiamondStrider1\SerDe\Reflect\Type;

final class Parser
{
	private ParseErrors $errors;

	/**
	 * @param array<string, mixed> $data
	 * @param array<int, string>   $baseKeys
	 */
	public function __construct(
		private array $data,
		private array $baseKeys = [],
		?ParseErrors $errors = null,
	) {
		$this->errors = $errors ?? new ParseErrors();
	}

	public function getErrors(): ParseErrors
	{
		return $this->errors;
	}

	public function traverse(string $key): Parser
	{
		return new Parser($this->data, [...$this->baseKeys, $key], $this->errors);
	}

	public function getQualifiedKeyName(string $key): string
	{
		return implode('.', [...$this->baseKeys, $key]);
	}

	public function getRawValue(string $key): mixed
	{
		$base = &$this->data;
		foreach ($this->baseKeys as $baseKey) {
			$nextBase = &$base[$baseKey] ?? null;
			if (!is_array($nextBase)) {
				return null;
			}
			$base = &$nextBase;
		}

		return $base[$key] ?? null;
	}

	/**
	 * @phpstan-return PossibleValue<void>|PossibleValue<string>|PossibleValue<null>
	 */
	public function takeNullableString(string $key): PossibleValue
	{
		$value = $this->getRawValue($key);
		if (null === $value) {
			return PossibleValue::value(null);
		}
		if (!is_string($value)) {
			$this->errors->reportError(new MismatchTypeError(
				key: $this->getQualifiedKeyName($key),
				expectedType: 'string or null',
				gotType: Type::getName($value),
			));

			return PossibleValue::empty();
		}

		return PossibleValue::value($value);
	}

	/**
	 * @phpstan-return PossibleValue<void>|PossibleValue<string>
	 */
	public function takeString(string $key): PossibleValue
	{
		$value = $this->getRawValue($key);
		if (null === $value) {
			return PossibleValue::empty();
		}
		if (!is_string($value)) {
			$this->errors->reportError(new MismatchTypeError(
				key: $this->getQualifiedKeyName($key),
				expectedType: 'string',
				gotType: Type::getName($value),
			));

			return PossibleValue::empty();
		}

		return PossibleValue::value($value);
	}

	/**
	 * @phpstan-return PossibleValue<void>|PossibleValue<int>|PossibleValue<null>
	 */
	public function takeNullableInt(string $key): PossibleValue
	{
		$value = $this->getRawValue($key);
		if (null === $value) {
			return PossibleValue::value(null);
		}
		if (!is_int($value)) {
			$this->errors->reportError(new MismatchTypeError(
				key: $this->getQualifiedKeyName($key),
				expectedType: 'int or null',
				gotType: Type::getName($value),
			));

			return PossibleValue::empty();
		}

		return PossibleValue::value($value);
	}

	/**
	 * @phpstan-return PossibleValue<void>|PossibleValue<int>
	 */
	public function takeInt(string $key): PossibleValue
	{
		$value = $this->getRawValue($key);
		if (null === $value) {
			return PossibleValue::empty();
		}
		if (!is_int($value)) {
			$this->errors->reportError(new MismatchTypeError(
				key: $this->getQualifiedKeyName($key),
				expectedType: 'int',
				gotType: Type::getName($value),
			));

			return PossibleValue::empty();
		}

		return PossibleValue::value($value);
	}

	/**
	 * @phpstan-return PossibleValue<void>|PossibleValue<float>|PossibleValue<null>
	 */
	public function takeNullableFloat(string $key): PossibleValue
	{
		$value = $this->getRawValue($key);
		if (null === $value) {
			return PossibleValue::value(null);
		}
		if (!is_float($value)) {
			$this->errors->reportError(new MismatchTypeError(
				key: $this->getQualifiedKeyName($key),
				expectedType: 'float or null',
				gotType: Type::getName($value),
			));

			return PossibleValue::empty();
		}

		return PossibleValue::value($value);
	}

	/**
	 * @phpstan-return PossibleValue<void>|PossibleValue<float>
	 */
	public function takeFloat(string $key): PossibleValue
	{
		$value = $this->getRawValue($key);
		if (null === $value) {
			return PossibleValue::empty();
		}
		if (!is_float($value)) {
			$this->errors->reportError(new MismatchTypeError(
				key: $this->getQualifiedKeyName($key),
				expectedType: 'float',
				gotType: Type::getName($value),
			));

			return PossibleValue::empty();
		}

		return PossibleValue::value($value);
	}

	/**
	 * @phpstan-return PossibleValue<void>|PossibleValue<bool>|PossibleValue<null>
	 */
	public function takeNullableBool(string $key): PossibleValue
	{
		$value = $this->getRawValue($key);
		if (null === $value) {
			return PossibleValue::value(null);
		}
		if (!is_bool($value)) {
			$this->errors->reportError(new MismatchTypeError(
				key: $this->getQualifiedKeyName($key),
				expectedType: 'bool or null',
				gotType: Type::getName($value),
			));

			return PossibleValue::empty();
		}

		return PossibleValue::value($value);
	}

	/**
	 * @phpstan-return PossibleValue<void>|PossibleValue<bool>
	 */
	public function takeBool(string $key): PossibleValue
	{
		$value = $this->getRawValue($key);
		if (null === $value) {
			return PossibleValue::empty();
		}
		if (!is_bool($value)) {
			$this->errors->reportError(new MismatchTypeError(
				key: $this->getQualifiedKeyName($key),
				expectedType: 'bool',
				gotType: Type::getName($value),
			));

			return PossibleValue::empty();
		}

		return PossibleValue::value($value);
	}
}
