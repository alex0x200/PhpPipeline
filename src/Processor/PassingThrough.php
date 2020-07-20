<?php declare(strict_types = 1);

namespace PhpPipeline\Processor;

final class PassingThrough implements ProcessorInterface
{
    /**
     * @param mixed $payload
     * @param callable ...$steps
     * @return mixed
     */
    public function process($payload, callable ...$steps)
    {
        foreach ($steps as $step) {
            $payload = $step($payload);
        }

        return $payload;
    }
}