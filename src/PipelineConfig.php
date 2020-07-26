<?php declare(strict_types = 1);

namespace PhpPipeline;

use PhpPipeline\Processor\PassingThrough;
use PhpPipeline\Processor\ProcessorInterface;
use PhpPipeline\Result\ByDefaultFactory;
use PhpPipeline\Result\FactoryInterface;
use PhpPipeline\Result\PipelineResultInterface;

class PipelineConfig implements ConfigInterface
{
    private ProcessorInterface $processor;
    private FactoryInterface $factory;

    /**
     * @param ProcessorInterface|null $processor
     * @param FactoryInterface|null $factory
     */
    public function __construct(
        ProcessorInterface $processor = null,
        FactoryInterface $factory = null
    ) {
        $this->processor = $processor ?? new PassingThrough();
        $this->factory = $factory ?? new ByDefaultFactory();
    }

    /**
     * @param mixed $payload
     * @param callable ...$pipes
     * @return Result\PipelineResultInterface
     */
    public function process($payload, callable ...$pipes): PipelineResultInterface
    {
        return $this->factory->createResult(
            $this->processor->passThroughPipes($payload, ...$pipes)
        );
    }
}