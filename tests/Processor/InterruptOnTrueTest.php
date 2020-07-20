<?php declare(strict_types = 1);

namespace PhpPipeline\Test\Processor;

use PhpPipeline\Pipeline;
use PhpPipeline\Processor\InterruptOnTrue;
use PHPUnit\Framework\TestCase;

final class InterruptOnTrueTest extends TestCase
{
    /**
     * @dataProvider ProviderForInterruptOnTrue
     * @param callable $checkFunc
     * @param array<callable> $pipes
     * @param array<int> $payload
     * @param array<int> $expected
     */
    public function testInterruptOnTrue(callable $checkFunc, array $pipes, array $payload, array $expected): void
    {
        $processor = new InterruptOnTrue($checkFunc);
        $pipeline = new Pipeline($processor, ...$pipes);

        self::assertEquals($expected, $pipeline->process($payload));
    }

    /**
     * @return  array<string, array<string, array<int, (Closure(array):array)|int>|(Closure(array): bool)>>
     */
    public function ProviderForInterruptOnTrue(): array
    {
        return [
            'emptyPipeline' => [
                'check' => function(array $payload): bool {
                    return count($payload) > 3;
                },
                'pipes' => [],
                'payload' => [1, 2, 3],
                'resultAfterPipes' => [1, 2, 3],
            ],
            'interrupted' => [
                'check' => function(array $payload): bool {
                    return count($payload) > 3;
                },
                'pipes' => [
                    function (array $payload): array {
                        return array_map(fn(int $value) => $value * 2, $payload);
                    },
                    function (array $payload): array {
                        return array_merge($payload, [100]);
                    },
                    function (array $payload): array {
                        return array_map(fn(int $value) => $value * 10, $payload);
                    },
                ],
                'payload' => [1, 2, 3],
                'resultAfterPipes' => [2, 4, 6, 100],
            ],
            'passingThroughAllPipes' => [
                'check' => function(array $payload): bool {
                    return count($payload) > 999;
                },
                'pipes' => [
                    function (array $payload): array {
                        return array_map(fn(int $value) => $value * 2, $payload);
                    },
                    function (array $payload): array {
                        return array_merge($payload, [100]);
                    },
                    function (array $payload): array {
                        return array_map(fn(int $value) => $value * 10, $payload);
                    },
                ],
                'payload' => [1, 2, 3],
                'resultAfterPipes' => [20, 40, 60, 1000],
            ],
        ];
    }
}
