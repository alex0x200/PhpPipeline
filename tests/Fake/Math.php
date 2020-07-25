<?php declare(strict_types = 1);

namespace Test\Fake;

class Math
{
    public function multiplication(int $payload): int
    {
        return $payload * 2;
    }

    public function __invoke(int $payload): int
    {
        return $payload;
    }
}