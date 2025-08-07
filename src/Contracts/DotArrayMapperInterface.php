<?php

declare(strict_types=1);

namespace Kasperhansen\DotArrayMapper\Contracts;

/**
 * @author Kasper Hansen <kasper.h90@gmail.com>
 */
interface DotArrayMapperInterface
{
    /**
     * Set the data to be mapped.
     *
     * @param array<string, mixed> $data
     */
    public function setData(array $data): void;

    /**
     * Set the mapping for the data.
     *
     * @param array<string, string> $mapping
     */
    public function map(array $mapping): void;

    /**
     * Extract the mapped data as a standard object.
     *
     * @return array<string, mixed>
     */
    public function extract(): array;
}
