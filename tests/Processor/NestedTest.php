<?php declare(strict_types = 1);

namespace Test\Processor;

use PhpPipeline\Pipeline;
use PhpPipeline\PipelineException;
use PhpPipeline\Processor\Nested;
use PHPUnit\Framework\TestCase;
use Test\Fake\Math;
use Test\Fake\MathSecond;

final class NestedTest extends TestCase
{
    /**
     * @dataProvider ProviderForVia
     * @param array<callable> $pipes
     * @param mixed $payload
     * @param mixed $expected
     */
    public function testViaSuccess(array $pipes, $payload, $expected): void
    {
        $pipeline = new Pipeline(new Nested(), ...$pipes);

        self::assertEquals($expected, $pipeline->process($payload));
    }

    /**
     * @return array<string, array<string, array<int, (Closure(int):float)|Test\Fake\Math|Test\Fake\MathSecond>|float|int>>
     */
    public function ProviderForVia(): array
    {
        return [
            'noPipes' => [
                'pipes' => [],
                'payload' => 1488,
                'result' => 1488,
            ],
            'multiplePipes' => [
                'pipes' => [
                    new Math(),
                    new MathSecond(),
                    fn(int $payload): float => $payload * 1.91
                ],
                'payload' => 1488,
                'resultAfterPipes' => 1488 * 2 * 3 * 1.91,
            ],
        ];
    }

    /**
     * @return void
     * @throws PipelineException
     */
    public function testViaClosure(): void
    {
        $processor = new Nested();
        $payload = 100;
        $mul = 10;

        $result = $processor->passThroughPipes($payload, fn(int $payload): int => $payload * $mul);

        self::assertEquals($payload * $mul, $result);
    }

    /**
     * @return void
     */
    public function testViaMethodNotExist(): void
    {
        $processor = new Nested();
        $payload = 100;
        $pipe = new class() {
            public function __invoke(int $payload): int
            {
                return $payload;
            }
        };

        $result = $processor->passThroughPipes($payload, $pipe);

        self::assertEquals($payload, $result);
    }

    /**
     * @throws PipelineException
     */
    public function testViaError(): void
    {
        $this->expectException(PipelineException::class);
        $processor = new Nested();
        $payload = 100;
        $pipe = new class() {
            /**
             * @param array<int> $payload
             * @return array<int>
             */
            public function __invoke(array $payload): array
            {
                return $payload;
            }
        };

        $processor->passThroughPipes($payload, $pipe);
    }
}