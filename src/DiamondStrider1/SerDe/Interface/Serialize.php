<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Interface;

interface Serialize
{
	/**
	 * @return array<string, mixed>
	 */
	public function serialize(): array;
}
