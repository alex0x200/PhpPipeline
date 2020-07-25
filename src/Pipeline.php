<?php declare(strict_types = 1);

namespace PhpPipeline;

use PhpPipeline\Processor\ProcessorInterface;
use PhpPipeline\Result\PipelineResultInterface;
use PhpPipeline\Result\PipelineResultFactory;

final class Pipeline implements PipelineInterface
{
    private ProcessorInterface $processor;
    /**
     * @var callable[]
     */
    private array $pipes;

    private PipelineResultFactory $resultFactory;

    /**
     * @param ProcessorInterface $processor
     * @param callable ...$pipes
     */
    public function __construct(
        ProcessorInterface $processor,
        callable ...$pipes
    ) {
        $this->processor = $processor;
        $this->pipes = $pipes;
        $this->resultFactory = new PipelineResultFactory();
    }

    /**
     * @param callable ...$pipes
     * @return PipelineInterface
     */
    public function append(callable ...$pipes): PipelineInterface
    {
        return new self($this->processor, ...array_merge($this->pipes, $pipes));
    }

    /**
     * @param mixed $payload
     * @return PipelineResultInterface
     */
    public function send($payload): PipelineResultInterface
    {
        return $this->resultFactory->createResult($this->processor->process($payload, ...$this->pipes));
    }

    /**
     * This workaround allows you add to objects into pipeline, __invoke() func
     * method will be called like just like callable.
     * @param mixed $payload
     * @return mixed|void
     */
    public function __invoke($payload)
    {
        $this->send($payload);
    }
}