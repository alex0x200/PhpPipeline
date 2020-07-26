<?php declare(strict_types = 1);

namespace Test\Processor;

use PhpPipeline\Pipeline;
use PhpPipeline\PipelineConfig;
use PhpPipeline\Processor\InterruptOnFalse;
use PhpPipeline\Processor\InterruptOnTrue;
use PhpPipeline\Result\ByDefaultFactory;
use PHPUnit\Framework\TestCase;

final class InterruptOnFalseTest extends TestCase
{
    /**
     * @dataProvider ProviderForInterruptOnFalse
     * @param callable $checkFunc
     * @param array<callable> $pipes
     * @param array<int> $payload
     * @param array<int> $expected
     */
    public function testInterruptOnFalse(callable $checkFunc, array $pipes, array $payload, array $expected): void
    {
        $pipeline = new Pipeline(
            new PipelineConfig(
                new InterruptOnFalse($checkFunc),
                new ByDefaultFactory()
            ),
            ...$pipes
        );

        self::assertEquals($expected, $pipeline->resultOf($payload)->thenReturn());
    }

    /**
     * @return  array<string, array<string, array<int, (Closure(array):array)|int>|(Closure(array): bool)>>
     */
    public function ProviderForInterruptOnFalse(): array
    {
        return [
            'interrupted' => [
                'check' => function (array $payload): bool {
                    return count($payload) > 4;
                },
                'pipes' => [
                    function (array $payload): array {
                        return array_slice($payload, 1, count($payload) - 1);
                    },
                    function (array $payload): array {
                        return array_slice($payload, 1, count($payload) - 1);
                    },
                ],
                'payload' => [10, 20, 30, 40, 50],
                'resultAfterPipes' => [20, 30, 40, 50],
            ],
            'passingThroughAllPipes' => [
                'check' => function (array $payload): bool {
                    return count($payload) > 1;
                },
                'pipes' => [
                    function (array $payload): array {
                        return array_slice($payload, 1, count($payload) - 1);
                    },
                    function (array $payload): array {
                        return array_slice($payload, 1, count($payload) - 1);
                    },
                    function (array $payload): array {
                        return array_slice($payload, 1, count($payload) - 1);
                    },
                ],
                'payload' => [10, 20, 30, 40, 50],
                'resultAfterPipes' => [40, 50],
            ],
        ];
    }
}
