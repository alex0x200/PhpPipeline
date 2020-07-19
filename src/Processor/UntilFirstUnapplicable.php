<?php declare(strict_types = 1);

namespace alex0x200\Pipeline\Processor;

final class UntilFirstUnapplicable implements ProcessorInterface
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
    public function process($payload, callable ...$steps)
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