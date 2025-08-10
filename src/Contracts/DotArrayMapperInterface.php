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
    public function setData(array $data): self;

    /**
     * Set the mapping for the data.
     *
     * @param array<string, string> $mapping
     * @return self
     */
    public function map(array $mapping): self;

    /**
     * Apply a filter to a specific key in the mapped data.
     *
     * @param string $key
     * @param callable $filter
     * @return self
     */
    public function addFilter(string $key, callable $filter): self;

    /**
     * Bulk method for adding multiple filters to the mapped data.
     *
     * @param array<string, callable> $filters
     * @return self
     */
    public function addFilters(array $filters): self;

    /**
     * Extract the mapped data to an associative array.
     *
     * @deprecated Use `transform()` instead.
     * @return array<string, mixed>
     */
    public function extract(): array;

    /**
     * Transform the mapped data to an associative array.
     *
     * @return array<string, mixed>
     */
    public function transform(): array;
}
