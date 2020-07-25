<?php declare(strict_types = 1);

namespace PhpPipeline;

final class PipelineResult implements PipelineResultInterface
{
    /**
     * @var mixed $payload
     */
    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function then(\Closure $then)
    {
        return $then($this->payload);
    }

    public function thenReturn()
    {
        return $this->payload;
    }
}