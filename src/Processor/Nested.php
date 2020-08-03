<?php declare(strict_types = 1);

namespace PhpPipeline\Processor;

use PhpPipeline\PipelineException;

class Nested implements ProcessorInterface
{
    /**
     * @param mixed $payload
     * @param callable ...$steps
     * @return mixed
     * @throws PipelineException
     */
    public function passThroughPipes($payload, callable ...$steps)
    {
        $reducedPipes = array_reduce(array_reverse($steps), $this->carry(), fn($value) => $value);

        try {
            return $reducedPipes($payload);
        } catch (\Throwable $e) {
            throw new PipelineException(
                sprintf("Failed while pipes were processing: %s", $e->getMessage()),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @return \Closure
     * @throws PipelineException
     */
    private function carry(): \Closure
    {
        return function ($carry, callable $pipe): \Closure {
            return function ($payload) use ($carry, $pipe) {
                return $carry($pipe($payload, $carry));
            };
        };
    }
}
