<?php declare(strict_types = 1);

namespace PhpPipeline;

interface PipelineResultInterface
{
    public function then(\Closure $then);

    public function thenReturn();
}