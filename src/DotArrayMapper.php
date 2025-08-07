<?php

declare(strict_types=1);

namespace Kasperhansen\DotArrayMapper;

use Kasperhansen\DotArrayMapper\Contracts\DotArrayMapperInterface;

/**
 * @author Kasper Hansen <kasper.h90@gmail.com>
 */
class DotArrayMapper implements DotArrayMapperInterface
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
     * @inheritDoc
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function map(array $mapping): void
    {
        $this->mapping = $mapping;
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
        $result = [];

        foreach ($this->mapping as $key => $path) {
            $value = $this->getValueFromPath($path);

            if (null === $value) {
                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
