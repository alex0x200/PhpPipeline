<?php declare(strict_types = 1);

namespace PhpPipeline\Test\Processor;

use PhpPipeline\Pipeline;
use PhpPipeline\Processor\PassingThrough;
use PHPUnit\Framework\TestCase;

final class PassingThroughTest extends TestCase
{
    /**
     * @dataProvider ProviderForPassingThrough
     * @param array<callable> $pipes
     * @param mixed $payload
     * @param mixed $expected
     */
    public function testPassingThrough(array $pipes, $payload, $expected): void
    {
        $processor = new PassingThrough();
        $pipeline = new Pipeline($processor, ...$pipes);

        self::assertEquals($expected, $pipeline->process($payload));
    }

    /**
     * @return array<string,array<string, array<int, (Closure(int): bool)|(Closure(int): int)>|int|string|true>>
     */
    public function ProviderForPassingThrough(): array
    {
        return [
            'emptyPipeline' => [
                'pipes' => [],
                'payload' => 'simple_string',
                'resultAfterPipes' => 'simple_string',
            ],
            'single' => [
                'pipes' => [
                    function (int $payload): int {
                        return $payload / 2;
                    },
                ],
                'payload' => 1488,
                'resultAfterPipes' => 744,
            ],
            'multiplePipelines' => [
                'pipes' => [
                    function (int $payload): int {
                        return $payload * 2;
                    },
                    function (int $payload): int {
                        return $payload * 3;
                    },
                    function (int $payload): bool {
                        return $payload > 1488;
                    },
                ],
                'payload' => 400,
                'resultAfterPipes' => true,
            ]
        ];
    }
}