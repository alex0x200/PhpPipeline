<?php declare(strict_types = 1);

namespace PhpPipeline\Processor;

/**
 * Finalizer
 */
final class ViaMethodAndThenTo implements ProcessorInterface
{
    private Via $via;
    /**
     * @var callable
     */
    private $then;

    /**
     * @param callable $then
     * @param string $methodName
     */
    public function __construct(callable $then, string $methodName = "__invoke")
    {
        $this->then = $then;
        $this->via = new Via($methodName);
    }

    /**
     * @param mixed $payload
     * @param callable ...$steps
     * @return mixed
     */
    public function process($payload, callable ...$steps)
    {
        $then = $this->then;
        return $then($this->via->process($payload, ...$steps));
    }
}
