<?php

declare(strict_types=1);

namespace App\Dtos;

use Infra\Interfaces\RequestDtoInterface;

class PostRequestDto implements RequestDtoInterface
{
    public function __construct(
        public string $title,
        public ?string $content = '',
    ) {}

    /**
     * @param  string[]  $data
     */
    public static function fromRequestData(array $data): self
    {
        $title = $data['title'] ?? '';

        $content = $data['content'] ?? '';

        return new self($title, $content);
    }
}
