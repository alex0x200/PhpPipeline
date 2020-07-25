<?php declare(strict_types = 1);

namespace Fake;

class ConcatSecond implements Concatable
{
    public  function concat(string $payload): string
    {
        return sprintf("%s how are", $payload);
    }
    public function __invoke(string $payload): string
    {
        return $payload;
    }
}