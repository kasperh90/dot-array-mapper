<?php

declare(strict_types=1);

namespace Kasperhansen\DotArrayMapper;

use Kasperhansen\DotArrayMapper\Contracts\DotArrayMapperInterface;

/**
 * @author Kasper Hansen <kasper.h90@gmail.com>
 */
final class DotArrayMapper implements DotArrayMapperInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * @var array<string, string>
     */
    private array $mapping = [];

    /**
     * @var array<string, callable>
     */
    private array $filters = [];

    /**
     * @inheritDoc
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function map(array $mapping): self
    {
        $this->mapping = $mapping;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addFilter(string $key, callable $filter): self
    {
        $this->filters[$key] = $filter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addFilters(array $filters): self
    {
        $this->filters += $filters;

        return $this;
    }

    /**
     * Get the value from the data array using a dot notation path.
     *
     * @param string $path
     * @return mixed
     */
    private function getValueFromPath(string $path): mixed
    {
        $keys = explode('.', $path);
        $value = $this->data;

        foreach ($keys as $key) {
            if (is_array($value) && array_key_exists($key, $value)) {
                $value = $value[$key];

                continue;
            }

            return null; // Return null if the path does not exist
        }

        return $value;
    }


    /**
     * @inheritDoc
     */
    public function extract(): array
    {
        trigger_error(
            'DotArrayMapper::extract() is deprecated. Use transform() instead.',
            E_USER_DEPRECATED
        );

        return $this->transform();
    }

    /**
     * @inheritDoc
     */
    public function transform(): array
    {
        $result = [];

        foreach ($this->mapping as $key => $path) {
            $value = $this->getValueFromPath($path);

            // Apply filters if any are defined for the key
            if (array_key_exists($key, $this->filters)) {
                $filter = $this->filters[$key];

                if (null !== $value) {
                    $value = $filter($value);
                }
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
