<?php

declare(strict_types=1);

namespace Infra\Interfaces;

interface RequestDtoInterface
{
    /**
     * @param  string[]  $data
     */
    public static function fromRequestData(array $data): self;
}
