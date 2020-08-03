<?php declare(strict_types = 1);

namespace PhpPipeline\Processor;

class PassingThrough implements ProcessorInterface
{
    /**
     * @param mixed $payload
     * @param callable ...$steps
     * @return mixed
     */
    public function passThroughPipes($payload, callable ...$steps)
    {
        foreach ($steps as $step) {
            $payload = $step($payload);
        }

        return $payload;
    }
}