<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Interface;

use DiamondStrider1\SerDe\Parse\ParseErrorsEncounteredException;
use DiamondStrider1\SerDe\Parse\Parser;

interface Deserialize
{
	/**
	 * @param Parser|array<string, mixed> $data
	 *
	 * @throws ParseErrorsEncounteredException
	 */
	public static function deserialize(Parser|array $data): self;
}
