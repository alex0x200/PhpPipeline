<?php declare(strict_types = 1);

namespace PhpPipeline;

use PhpPipeline\Processor\ProcessorInterface;

interface PipelineInterface
{
    /**
     * @param ProcessorInterface $processInterface
     * @param callable ...$steps
     */
    public function __construct(ProcessorInterface $processInterface, callable ...$steps);

    /**
     * Append pipes to pipeline
     * @param callable ...$operation
     * @return PipelineInterface
     */
    public function append(callable ...$operation): PipelineInterface;

    /**
     * @param mixed $payload
     * @return mixed
     */
    public function process($payload);
}