<?php declare(strict_types = 1);

namespace PhpPipeline;

use PhpPipeline\Processor\ProcessorInterface;

final class Pipeline implements PipelineInterface
{
    private ProcessorInterface $processor;
    /**
     * @var callable[]
     */
    public array $pipes;

    /**
     * @param ProcessorInterface $processor
     * @param callable ...$pipes
     */
    public function __construct(ProcessorInterface $processor, callable ...$pipes)
    {
        $this->processor = $processor;
        $this->pipes = $pipes;
    }

    /**
     * @param callable ...$pipes
     * @return PipelineInterface
     */
    public function append(...$pipes): PipelineInterface
    {
        return new self($this->processor, ...array_merge($this->pipes, $pipes));
    }

    /**
     * @param mixed $payload
     * @return mixed|null
     */
    public function process($payload)
    {
        return $this->processor->process($payload, ...$this->pipes);
    }
}