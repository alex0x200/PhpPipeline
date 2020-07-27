<?php declare(strict_types = 1);

namespace PhpPipeline;

use PhpPipeline\Processor\ProcessorInterface;
use PhpPipeline\Result\FactoryInterface;
use PhpPipeline\Result\PipelineResultInterface;

interface ConfigInterface
{
    /**
     * @param ProcessorInterface $processor
     * @param FactoryInterface $factory
     */
    public function __construct(ProcessorInterface $processor, FactoryInterface $factory);

    /**
     * @param mixed $payload
     * @param callable ...$pipes
     * @return Result\PipelineResultInterface
     */
    public function process($payload, callable ...$pipes): PipelineResultInterface;
}