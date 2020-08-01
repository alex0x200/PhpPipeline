<?php declare(strict_types = 1);

namespace Test;

use PhpPipeline\Pipeline;
use PhpPipeline\PipelineBuilder;
use PHPUnit\Framework\TestCase;

class PipelineBuilderTest extends TestCase
{
    /**
     * @return void
     */
    public function testBuild(): void
    {
        $builder = new PipelineBuilder();
        $pipeline = $builder->build();

        self::assertInstanceOf(Pipeline::class, $pipeline);
    }

    /**
     * @return void
     */
    public function testAdd(): void
    {
        $builder = new PipelineBuilder();
        $builder->add(fn(int $payload): int => $payload * 2);
        $builder->add(fn(int $payload): int => $payload * 3);

        $payload = 100;
        $pipeline = $builder->build();

        self::assertEquals($payload * 2 * 3, $pipeline->process($payload));
    }
}