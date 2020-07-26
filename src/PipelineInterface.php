<?php declare(strict_types = 1);

namespace PhpPipeline;

use PhpPipeline\Processor\ProcessorInterface;

interface PipelineInterface extends CallableInterface
{
    /**
     * @param ConfigInterface $config
     * @param callable ...$steps
     */
    public function __construct(ConfigInterface $config, callable ...$steps);

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
    public function resultOf($payload);
}