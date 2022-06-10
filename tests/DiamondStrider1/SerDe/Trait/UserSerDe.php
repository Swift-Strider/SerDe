<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\tests\Trait;

use DiamondStrider1\SerDe\Attribute\Field;
use DiamondStrider1\SerDe\Attribute\Transformer\PrimitiveArrayTransformer;
use DiamondStrider1\SerDe\Interface\SerDe;
use DiamondStrider1\SerDe\Trait\SerDeTrait;

final class UserSerDe implements SerDe
{
	use SerDeTrait;

	public function __construct(
		#[Field()]
		public string $name,
		#[Field()]
		public int $coins,
		#[Field()]
		public float $money,
		#[Field('on-vacation')]
		public ?bool $onVacation,
		#[Field()]
		#[PrimitiveArrayTransformer('string')]
		public array $cartItems,
		#[Field()]
		public array $unstructuredArray,
		#[Field()]
		public ?Bag $bag = null,
	) {
	}
}
