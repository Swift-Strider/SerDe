<?php

declare(strict_types=1);

namespace DiamondStrider1\SerDe\tests\Trait;

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

final class SerializeTraitTest extends TestCase
{
	public function testSerialize(): void
	{
		$data = (new UserSerDe(
			'user', 5, 2.3, true, ['hi'], [1, 'a' => 2, 3]
		))->serialize();
		assertEquals([
			'name' => 'user',
			'coins' => 5,
			'money' => 2.3,
			'on-vacation' => true,
			'cartItems' => ['hi'],
			'unstructuredArray' => [1, 'a' => 2, 3],
			'bag' => null,
		], $data, 'expect serialize to work');
	}
}
