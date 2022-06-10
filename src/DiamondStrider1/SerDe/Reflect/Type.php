<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Reflect;

use LogicException;

final class Type
{
	public static function check(string $primitiveType, mixed $value): bool
	{
		return match ($primitiveType) {
			'string' => is_string($value),
			'bool' => is_bool($value),
			'int' => is_int($value),
			'float' => is_float($value),
			'array' => is_array($value),
			default => throw new LogicException("$primitiveType is an unsupported primitive type!")
		};
	}

	public static function getName(mixed $value): string
	{
		return match (true) {
			null === $value => 'null',
			is_string($value) => 'string',
			is_bool($value) => 'bool',
			is_int($value) => 'int',
			is_float($value) => 'float',
			is_array($value) => 'array',
			default => '<unknown>',
		};
	}
}
