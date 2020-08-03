<?php declare(strict_types = 1);

namespace Test\Fake;

class MathSecond
{
    /**
     * @param int $payload
     * @return int
     */
    public function __invoke(int $payload): int
    {
        return $payload * 3;
    }
}