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

        $mapper
            ->setData([
                'user_id' => 123,
                'user_details' => [
                    'first_name' => 'Kasper',
                    'last_name' => 'Hansen',
                    'flags' => [
                        'is_admin' => true,
                        'is_active' => false,
                    ],
                ],
            ])
            ->map([
                'id' => 'user_id',
                'firstName' => 'user_details.first_name',
                'lastName' => 'user_details.last_name',
                'isAdmin' => 'user_details.flags.is_admin',
                'isActive' => 'user_details.flags.is_active',
            ]);

        $data = $mapper->transform();
        $this::assertEquals(123, $data['id']);
        $this::assertEquals('Kasper', $data['firstName']);
        $this::assertEquals('Hansen', $data['lastName']);
        $this::assertTrue($data['isAdmin']);
        $this::assertFalse($data['isActive']);
    }

    public function testNonExistentPath(): void
    {
        $mapper = new DotArrayMapper();

        $mapper
            ->setData([
                'user_id' => 123,
                'user_details' => [
                    'first_name' => 'Kasper',
                    'last_name' => 'Hansen',
                ],
            ])
            ->map([
                'id' => 'user_id',
                'nonExistentField' => 'user_details.non_existent_field',
            ]);

        $data = $mapper->transform();
        $this::assertEquals(123, $data['id']);
        $this::assertNull($data['nonExistentField']);
    }

    public function testEmptyData(): void
    {
        $mapper = new DotArrayMapper();

        $mapper
            ->setData([])
            ->map([
                'id' => 'user_id',
                'firstName' => 'user_details.first_name',
            ]);

        $data = $mapper->transform();
        $this::assertArrayHasKey('id', $data);
        $this::assertNull($data['id']);
        $this::assertArrayHasKey('firstName', $data);
        $this::assertNull($data['firstName']);
    }

    public function testEmptyDataAndMapping(): void
    {
        $mapper = new DotArrayMapper();

        $mapper
            ->setData([])
            ->map([]);

        $data = $mapper->transform();
        $this::assertEmpty($data);
    }

    public function testDuplicateKeyNames(): void
    {
        $mapper = new DotArrayMapper();

        $mapper
            ->setData([
                'name' => 'John Doe',
                'partner' => [
                    'name' => 'Jane Doe',
                ],
            ])
            ->map([
                'name' => 'name',
                'partner' => 'partner.name',
            ]);

        $data = $mapper->transform();
        $this::assertEquals('John Doe', $data['name']);
        $this::assertEquals('Jane Doe', $data['partner']);
    }

    public function testFilters(): void
    {
        $mapper = new DotArrayMapper();

        $mapper
            ->setData([
                'name' => 'john doe',
            ])
            ->map([
                'name' => 'name',
            ])
            ->addFilter('name', fn($value) => ucwords($value));

        $data = $mapper->transform();
        $this::assertEquals('John Doe', $data['name']);
    }

    public function testBulkFilters(): void
    {
        $mapper = new DotArrayMapper();

        $mapper
            ->setData([
                'name' => 'john doe',
                'age' => 30,
            ])
            ->map([
                'name' => 'name',
                'age' => 'age',
            ])
            ->addFilter('name', fn($value) => ucwords($value))
            ->addFilters([
                'age' => fn($value) => $value + 5,
            ]);

        $data = $mapper->transform();
        $this::assertEquals('John Doe', $data['name']);
        $this::assertEquals(35, $data['age']);
    }

    public function testMissingIntermediateKeyReturnsNull(): void
    {
        $mapper = new DotArrayMapper();

        $mapper
            ->setData([
                'user' => [
                    // 'profile' => missing
                ],
            ])
            ->map([
                'name' => 'user.profile.name',
            ]);

        $data = $mapper->transform();
        $this::assertArrayHasKey('name', $data);
        $this::assertNull($data['name']);
    }

    public function testNestedMappingWithMissingKeys(): void
    {
        $mapper = new DotArrayMapper();

        $mapper
            ->setData([
                'user' => [
                    'details' => [
                        // 'address' => missing
                    ],
                ],
            ])
            ->map([
                'userAddress' => 'user.details.address',
            ]);

        $data = $mapper->transform();
        $this::assertArrayHasKey('userAddress', $data);
        $this::assertNull($data['userAddress']);
    }

    public function testUnmappedDataReturnsNull(): void
    {
        $mapper = new DotArrayMapper();

        $mapper
            ->setData([
                'user_id' => 123,
                'user_details' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
            ])
            ->map([
                'id' => 'user_id',
                // No mapping for firstName and lastName
            ]);

        $data = $mapper->transform();
        $this::assertEquals(123, $data['id']);
        $this::assertArrayNotHasKey('firstName', $data);
        $this::assertArrayNotHasKey('lastName', $data);
    }

    public function testApplyingFilterOnNullValue(): void
    {
        $mapper = new DotArrayMapper();

        $mapper
            ->setData([
                'user' => [
                    'name' => null,
                ],
            ])
            ->map([
                'userName' => 'user.name',
            ])
            ->addFilter('userName', fn($value) => strtoupper($value));

        $data = $mapper->transform();
        $this::assertNull($data['userName']);
    }
}
