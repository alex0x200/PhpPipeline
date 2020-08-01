<?php declare(strict_types = 1);

namespace Test;

use PhpPipeline\Pipeline;
use PhpPipeline\Processor\PassingThrough;
use PHPUnit\Framework\TestCase;

class PipelineTest extends TestCase
{
    public function testPipelineAppend(): void
    {
        $pipeline = new Pipeline(new PassingThrough(), fn(int $payload): int => $payload);
        $func = fn(int $payload): int => $payload * 100;
        $payload = 20;
        self::assertEquals($func($payload), $pipeline->append($func)->process($payload));
    }

    /**
     * @dataProvider providerForProcessAndInvoke
     * @param int $payload
     * @param int $expected
     * @param array<callable> ...$pipes
     */
    public function testProcess(int $payload, int $expected, array $pipes): void
    {
        $pipeline = new Pipeline(new PassingThrough(), ...$pipes);
        self::assertEquals($expected, $pipeline->process($payload));
    }

    /**
     * @dataProvider providerForProcessAndInvoke
     * @param int $payload
     * @param int $expected
     * @param array<callable> ...$pipes
     */
    public function testInvoke(int $payload, int $expected, array $pipes): void
    {
        $pipeline = new Pipeline(new PassingThrough(), ...$pipes);
        self::assertEquals($expected, $pipeline($payload));
    }

    /**
     * @return array<string, array<string, array<int, \Closure(int):int>|int>>
     */
    public function providerForProcessAndInvoke(): array
    {
        return [
            'singlePipe' => [
                'payload' => 1,
                'expected' => 2,
                'pipes' => [
                    fn(int $payload): int => $payload * 2,
                ],
            ],
            'multiple' => [
                'payload' => 1,
                'expected' => 24,
                'pipes' => [
                    fn(int $payload): int => $payload * 2,
                    fn(int $payload): int => $payload * 3,
                    fn(int $payload): int => $payload * 4,
                ],
            ],
        ];
    }
}