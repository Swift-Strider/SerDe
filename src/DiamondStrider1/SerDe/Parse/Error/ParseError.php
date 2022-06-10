<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Parse\Error;

interface ParseError
{
	public function describe(): string;
}
