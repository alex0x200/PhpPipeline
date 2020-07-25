<?php declare(strict_types = 1);

namespace PhpPipeline\Result;

interface PipelineResultFactoryInterface
{
    /**
     * @param mixed $payload
     * @return PipelineResultInterface
     */
    public function createResult($payload): PipelineResultInterface;
}