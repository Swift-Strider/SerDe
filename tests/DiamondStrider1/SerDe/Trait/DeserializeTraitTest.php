<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\tests\Trait;

use DiamondStrider1\SerDe\Parse\ParseErrorsEncounteredException;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

final class DeserializeTraitTest extends TestCase
{
	public function testDeserialize(): void
	{
		$data = [
			'name' => 'user',
			'coins' => 5,
			'money' => 2.3,
			'on-vacation' => true,
			'cartItems' => ['hi'],
			'unstructuredArray' => [1, 'a' => 2, 3],
		];
		$user = UserDeserialize::deserialize($data);
		$expected = new UserDeserialize(
			'user', 5, 2.3, true, ['hi'], [1, 'a' => 2, 3]
		);
		assertEquals($user, $expected, 'expect deserialize to work');
	}

	public function testDeserializeFail(): void
	{
		try {
			$data = [
				'name' => null,
				'coins' => 1.2,
				'money' => 2,
				'on-vacation' => null,
				'cartItems' => ['hi', 5],
				'unstructuredArray' => [1, 'a' => 2, 3],
			];
			UserDeserialize::deserialize($data);
			$this->assertTrue(false, 'DiamondStrider1\SerDe\Parse\ParseErrorsEncounteredException must be thrown.');
		} catch (ParseErrorsEncounteredException $e) {
			$errors = $e->getParseErrors();
			assertEquals("Expected \"name\" to be string, but null was given.\nExpected \"coins\" to be int, but float was given.\nExpected \"money\" to be float, but int was given.\nExpected \"cartItems.1\" to be string, but int was given.\n", $errors->toString(), 'expected error message to match expected');
		}
	}
}
