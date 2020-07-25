<?php declare(strict_types = 1);

namespace Test\Processor;

use PhpPipeline\Pipeline;
use PhpPipeline\Processor\Via;
use PHPUnit\Framework\TestCase;

final class ViaTest extends TestCase
{
    /**
     * @dataProvider ProviderForVia
     * @param array<callable> $pipes
     * @param mixed $payload
     * @param mixed $expected
     */
    public function testViaSuccess(array $pipes, $payload, $expected): void
    {
        $processor = new Via('ping');
        $pipeline = new Pipeline($processor, ...$pipes);

        self::assertEquals($expected, $pipeline->send($payload)->thenReturn());
    }

    /**
     * @return array<string,array<string, array<int, (Closure(int): bool)|(Closure(int): int)>|int|string|true>>
     */
    public function ProviderForVia(): array
    {
        return [
            'multiplePipes' => [
                'pipes' => [
                    new class() {
                        public  function ping(int $payload): int
                        {
                            return $payload * 2;
                        }
                        public function __invoke(int $payload): int
                        {
                            return $payload;
                        }
                    },
                    new class() {
                        public  function ping(int $payload): int
                        {
                            return $payload * 3;
                        }
                        public function __invoke(int $payload): int
                        {
                            return $payload;
                        }
                    },
                    new class() {
                        public  function ping(int $payload): int
                        {
                            return $payload * 4;
                        }
                        public function __invoke(int $payload): int
                        {
                            return $payload;
                        }
                    }
                ],
                'payload' => 1488,
                'resultAfterPipes' => 1488 * 2 * 3 * 4,
            ],
        ];
    }
}