<?php declare(strict_types = 1);

namespace alex0x200\Test\Pipeline\Processor;

use alex0x200\Pipeline\Pipeline;
use alex0x200\Pipeline\Processor\ThroughAll;
use PHPUnit\Framework\TestCase;

final class ThroughAllTest extends TestCase
{
    /**
     * @dataProvider ProviderForFirstApplicable
     * @param array<callable> $pipes
     * @param mixed $payload
     * @param mixed $expected
     */
    public function testFirstApplicable(array $pipes, $payload, $expected): void
    {
        $processor = new ThroughAll();
        $pipeline = new Pipeline($processor, ...$pipes);

        self::assertEquals($expected, $pipeline->process($payload));
    }

    /**
     * @return array<string,array<string, array<int, (Closure(int): bool)|(Closure(int): int)>|int|string|true>>
     */
    public function ProviderForFirstApplicable(): array
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