<?php declare(strict_types = 1);

namespace Fake;

class ConcatThird implements Concatable
{
    public  function concat(string $payload): string
    {
        return sprintf("%s you?!", $payload);
    }
    public function __invoke(string $payload): string
    {
        return $payload;
    }
}