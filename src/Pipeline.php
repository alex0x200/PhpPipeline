<?php declare(strict_types = 1);

namespace PhpPipeline;

use PhpPipeline\Processor\ProcessorInterface;

final class Pipeline implements PipelineInterface
{
    private ProcessorInterface $processor;
    /**
     * @var callable[]
     */
    private array $pipes;

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
     * @return mixed
     */
    public function process($payload)
    {
        return $this->processor->passThroughPipes($payload, ...$this->pipes);
    }

    /**
     * @param mixed $payload
     * @return mixed
     */
    public function __invoke($payload)
    {
        return $this->process($payload);
    }
}