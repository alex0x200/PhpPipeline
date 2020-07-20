<?php declare(strict_types = 1);

namespace PhpPipeline\Processor;

interface ProcessorInterface
{
    /**
     * @param mixed $payload
     * @param callable ...$steps
     * @return mixed
     */
    public function process($payload, callable ...$steps);
}