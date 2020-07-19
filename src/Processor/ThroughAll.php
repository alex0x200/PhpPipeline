<?php declare(strict_types = 1);

namespace alex0x200\Pipeline\Processor;

final class ThroughAll implements ProcessorInterface
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