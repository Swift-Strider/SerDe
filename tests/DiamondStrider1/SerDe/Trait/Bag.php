<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\tests\Trait;

use DiamondStrider1\SerDe\Attribute\Field;
use DiamondStrider1\SerDe\Interface\SerDe;
use DiamondStrider1\SerDe\Trait\SerDeTrait;

final class Bag implements SerDe
{
	use SerDeTrait;

	public function __construct(
		#[Field()]
		private string $contents,
	) {
	}
}
