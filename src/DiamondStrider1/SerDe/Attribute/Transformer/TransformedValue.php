<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Attribute\Transformer;

final class TransformedValue
{
	public function __construct(
		private mixed $value,
	) {
	}

	public function get(): mixed
	{
		return $this->value;
	}
}
