<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Field
{
	public function __construct(
		public ?string $name = null,
	) {
	}
}
