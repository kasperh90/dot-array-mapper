<?php

declare(strict_types=1);

namespace Kasperhansen\DotArrayMapper\Tests;

use Kasperhansen\DotArrayMapper\DotArrayMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @author Kasper Hansen <kasper.h90@gmail.com>
 */
#[CoversClass(DotArrayMapper::class)]
class DotArrayMapperTest extends TestCase
{
    public function testMapping(): void
    {
        $mapper = new DotArrayMapper();

        $mapper->setData([
            'user_id' => 123,
            'user_details' => [
                'first_name' => 'Kasper',
                'last_name' => 'Hansen',
                'flags' => [
                    'is_admin' => true,
                    'is_active' => false,
                ],
            ],
        ]);

        $mapper->map([
            'id' => 'user_id',
            'firstName' => 'user_details.first_name',
            'lastName' => 'user_details.last_name',
            'isAdmin' => 'user_details.flags.is_admin',
            'isActive' => 'user_details.flags.is_active',
        ]);

        $data = $mapper->extract();
        $this::assertEquals(123, $data['id']);
        $this::assertEquals('Kasper', $data['firstName']);
        $this::assertEquals('Hansen', $data['lastName']);
        $this::assertTrue($data['isAdmin']);
        $this::assertFalse($data['isActive']);
    }

    public function testNonExistentPath(): void
    {
        $mapper = new DotArrayMapper();

        $mapper->setData([
            'user_id' => 123,
            'user_details' => [
                'first_name' => 'Kasper',
                'last_name' => 'Hansen',
            ],
        ]);

        $mapper->map([
            'id' => 'user_id',
            'nonExistentField' => 'user_details.non_existent_field',
        ]);

        $data = $mapper->extract();
        $this::assertEquals(123, $data['id']);
        $this::assertNull($data['nonExistentField']);
    }

    public function testEmptyData(): void
    {
        $mapper = new DotArrayMapper();

        $mapper->setData([]);
        $mapper->map([
            'id' => 'user_id',
            'firstName' => 'user_details.first_name',
        ]);

        $data = $mapper->extract();
        $this::assertEmpty($data);
    }
}
