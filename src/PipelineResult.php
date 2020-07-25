<?php declare(strict_types = 1);

namespace PhpPipeline;

final class PipelineResult implements PipelineResultInterface
{
    /**
     * @var mixed $payload
     */
    private $payload;

    /**
     * @param mixed $payload
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @param \Closure $then
     * @return mixed
     */
    public function then(\Closure $then)
    {
        return $then($this->payload);
    }

    /**
     * @return mixed
     */
    public function thenReturn()
    {
        return $this->payload;
    }
}