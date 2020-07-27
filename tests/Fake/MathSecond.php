<?php declare(strict_types = 1);

namespace Test\Fake;

class MathSecond
{
    public function multiplication(int $payload): int
    {
        return $payload * 3;
    }

    public function __invoke(int $payload): int
    {
        return $payload;
    }
}