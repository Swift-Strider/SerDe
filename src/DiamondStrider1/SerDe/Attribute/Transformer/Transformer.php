<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Attribute\Transformer;

use DiamondStrider1\SerDe\Parse\Error\ParseError;

interface Transformer
{
	public function serialize(mixed $value): mixed;

	/**
	 * @return TransformedValue|ParseError|array<int, ParseError>
	 */
	public function deserialize(mixed $value, string $errorKey): TransformedValue|ParseError|array;
}
