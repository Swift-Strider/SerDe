<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Attribute\Transformer;

use Attribute;
use DiamondStrider1\SerDe\Parse\Error\MismatchTypeError;
use DiamondStrider1\SerDe\Parse\Error\ParseError;
use DiamondStrider1\SerDe\Reflect\Type;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class PrimitiveArrayTransformer implements Transformer
{
	use ArrayTransformerTrait;

	public function __construct(
		private string $primitiveType,
	) {
	}

	public function serializeElement(mixed $value): mixed
	{
		return $value;
	}

	public function deserializeElement(string $key, mixed $value): TransformedValue|ParseError
	{
		if (!Type::check($this->primitiveType, $value)) {
			return new MismatchTypeError(
				key: $key,
				expectedType: $this->primitiveType,
				gotType: Type::getName($value),
			);
		}

		return new TransformedValue($value);
	}
}
