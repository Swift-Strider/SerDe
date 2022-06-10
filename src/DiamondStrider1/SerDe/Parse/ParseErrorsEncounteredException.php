<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Parse;

use RuntimeException;

final class ParseErrorsEncounteredException extends RuntimeException
{
	public function __construct(
		private ParseErrors $errors
	) {
		parent::__construct("Errors while parsing:\n".$errors->toString('  '));
	}

	public function getParseErrors(): ParseErrors
	{
		return $this->errors;
	}
}
