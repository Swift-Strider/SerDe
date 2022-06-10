<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\Parse;

use DiamondStrider1\SerDe\Parse\Error\ParseError;

final class ParseErrors
{
	/** @var ParseError[] */
	private array $errors = [];

	public function reportError(ParseError $error): void
	{
		$this->errors[] = $error;
	}

	/**
	 * @return ParseError[]
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}

	public function count(): int
	{
		return count($this->errors);
	}

	public function toString(
		string $leftPadding = '', string $rightPadding = "\n"
	): string {
		$string = '';
		foreach ($this->errors as $error) {
			$string .= $leftPadding.$error->describe().$rightPadding;
		}

		return $string;
	}
}
