<?php declare(strict_types=1);

namespace Fake;

interface Concatable
{
    public function concat(string $payload): string;
}