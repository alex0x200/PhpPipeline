<?php declare(strict_types = 1);

namespace Fake;

class ConcatFirst implements Concatable
{
    public function concat(string $payload): string
    {
        return sprintf("%s guys", $payload);
    }
    public function __invoke(string $payload): string
    {
        return $payload;
    }
}