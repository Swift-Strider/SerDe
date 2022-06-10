<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Parse\Error;

final class MissingPropertyError implements ParseError
{
	public function __construct(
		private string $key,
		private string $expectedType,
	) {
	}

	public function describe(): string
	{
		return "Expected \"$this->key\" to be $this->expectedType, but nothing was given.";
	}
}
