<?php declare(strict_types = 1);

namespace PhpPipeline\Processor;

class InterruptOnFalse implements ProcessorInterface
{
    /**
     * @var callable
     */
    private $check;

    /**
     * @param callable $check
     */
    public function __construct(callable $check)
    {
        $this->check = $check;
    }

    /**
     * @param mixed $payload
     * @param callable ...$steps
     * @return mixed
     */
    public function passThroughPipes($payload, callable ...$steps)
    {
        $check = $this->check;
        foreach ($steps as $step) {
            $payload = $step($payload);

            if ($check($payload) !== true) {
                break;
            }
        }

        return $payload;
    }
}