<?php declare(strict_types=1);

namespace PhpPipeline;

use PhpPipeline\Processor\PassingThrough;
use PhpPipeline\Processor\ProcessorInterface;

final class PipelineBuilder implements PipelineBuilderInterface
{
    /**
     * @var callable[]
     */
    private array $pipes = [];

    /**
     * @param callable $pipe
     * @return self
     */
    public function add(callable $pipe): PipelineBuilderInterface
    {
        $this->pipes[] = $pipe;
        return $this;
    }

    public function build(ProcessorInterface $processor = null): PipelineInterface
    {
        return new Pipeline($processor ?? new PassingThrough(), ...$this->pipes);
    }
}