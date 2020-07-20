<?php declare(strict_types = 1);

namespace PhpPipeline;

interface CallableInterface
{
    /**
     * @param mixed $payload
     * @return mixed
     */
    public function __invoke($payload);
}