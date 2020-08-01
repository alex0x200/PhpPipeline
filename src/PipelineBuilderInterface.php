<?php declare(strict_types = 1);

namespace PhpPipeline;

use PhpPipeline\Processor\ProcessorInterface;

interface PipelineBuilderInterface
{
    /**
     * @param callable $pipe
     * @return self
     */
    public function add(callable $pipe): self;

    /**
     * Build a new Pipeline
     * @param ProcessorInterface|null $processor
     * @return PipelineInterface
     */
    public function build(ProcessorInterface $processor = null): PipelineInterface;
}