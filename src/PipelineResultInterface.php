<?php declare(strict_types = 1);

namespace PhpPipeline;

interface PipelineResultInterface
{
    /**
     * @param \Closure $then
     * @return mixed
     */
    public function then(\Closure $then);

    /**
     * @return mixed
     */
    public function thenReturn();
}