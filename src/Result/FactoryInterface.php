<?php declare(strict_types = 1);

namespace PhpPipeline\Result;

interface FactoryInterface
{
    /**
     * @param mixed $payload
     * @return PipelineResultInterface
     */
    public function createResult($payload): PipelineResultInterface;
}