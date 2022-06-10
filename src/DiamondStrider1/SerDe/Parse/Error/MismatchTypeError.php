<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Parse\Error;

final class MismatchTypeError implements ParseError
{
	public function __construct(
		private string $key,
		private string $expectedType,
		private string $gotType,
	) {
	}

	public function describe(): string
	{
		return "Expected \"$this->key\" to be $this->expectedType, but $this->gotType was given.";
	}
}
