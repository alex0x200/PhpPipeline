<?php declare(strict_types = 1);

namespace PhpPipeline\Processor;

interface ProcessorInterface
{
    /**
     * @param mixed $payload
     * @param callable ...$steps
     * @return mixed
     */
    public function passThroughPipes($payload, callable ...$steps);
}