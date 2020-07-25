<?php declare(strict_types = 1);

namespace PhpPipeline\Processor;

use PhpPipeline\PipelineException;

/**
 * Executes method on stack of pipes, __invoke()|\Closure() if there is no such method
 */
final class Via implements ProcessorInterface
{
    private string $methodName;

    /**
     * @param string $methodName
     */
    public function __construct(string $methodName)
    {
        $this->methodName = $methodName;
    }

    /**
     * @param mixed $payload
     * @param callable ...$steps
     * @return mixed
     */
    public function process($payload, callable ...$steps)
    {
        $reducedPipes = array_reduce(array_reverse($steps), $this->carry(), function($value) { return $value;});
        return $reducedPipes($payload);
    }

    /**
     * @return \Closure
     */
    private function carry(): \Closure
    {
        return function ($carry, $pipe) {
            return function ($payload) use ($carry, $pipe) {
                try {
                    // if $pipe is anonymous func just call it
                    if ($pipe instanceof \Closure) {
                        return $carry($pipe($payload, $carry));
                    }
                    if (is_object($pipe)) {
                        $params = [$payload];

                        $methodCallable = [$pipe, $this->methodName];
                        // if $pipe has `via` method call it - go through the flow
                        if (is_callable($methodCallable)) {
                            return $carry(call_user_func_array($methodCallable, $params));
                        }
                        // if $pipe hasn't `via`, but can be invoked - let's do this fallback
                        if (is_callable($pipe)) {
                            return $carry($pipe(...$params));
                        }

                        // wrong object passed to pipeline
                        throw new PipelineException(
                            sprintf(
                                "Pipe '%s' hasn't '%s' method and can't be invoked",
                                get_class($pipe),
                                $this->methodName
                            )
                        );
                    }
                    throw new PipelineException(
                        sprintf(
                            'Pipe must be \Closure or object with method \'%s\' or \'__invoke()\'',
                            $this->methodName
                        )
                    );
                } catch (\Exception $e) {
                    throw new PipelineException("Carry pipe crashed on: " . $e->getMessage(), $e->getCode(), $e);
                }
            };
        };
    }
}
