<?php declare(strict_types = 1);

namespace alex0x200\Pipeline\Processor;

interface ProcessorInterface
{
    /**
     * @param mixed $payload
     * @param callable ...$steps
     * @return mixed
     */
    public function process($payload, callable ...$steps);
}