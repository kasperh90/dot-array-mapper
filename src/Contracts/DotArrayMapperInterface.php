<?php

declare(strict_types=1);

namespace Kasperhansen\DotArrayMapper\Contracts;

interface DotArrayMapperInterface
{
    public function setData(array $data): void;

    public function map(array $mapping): void;

    public function extract(): object;
}