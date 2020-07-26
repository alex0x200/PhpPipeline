<?php declare(strict_types = 1);

namespace PhpPipeline;

use PhpPipeline\Processor\ProcessorInterface;
use PhpPipeline\Result\PipelineResultInterface;
use PhpPipeline\Result\ByDefaultFactory;

final class Pipeline implements PipelineInterface
{
    private ProcessorInterface $processor;
    /**
     * @var callable[]
     */
    private array $pipes;

    private ConfigInterface $config;

    /**
     * @param ConfigInterface $config
     * @param callable ...$pipes
     */
    public function __construct(
        ConfigInterface $config,
        callable ...$pipes
    ) {
        $this->config = $config;
        $this->pipes = $pipes;
    }

    /**
     * @param callable ...$pipes
     * @return PipelineInterface
     */
    public function append(callable ...$pipes): PipelineInterface
    {
        return new self($this->config, ...array_merge($this->pipes, $pipes));
    }

    /**
     * @param mixed $payload
     * @return PipelineResultInterface
     */
    public function resultOf($payload): PipelineResultInterface
    {
        return $this->config->process($payload, ...$this->pipes);
    }

    /**
     * This workaround allows you add to objects into pipeline, __invoke() func
     * method will be called like just like callable.
     * @param mixed $payload
     * @return mixed|void
     */
    public function __invoke($payload)
    {
        $this->resultOf($payload);
    }
}