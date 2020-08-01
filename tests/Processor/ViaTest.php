<?php declare(strict_types = 1);

namespace Test\Processor;

use PhpPipeline\Pipeline;
use PhpPipeline\PipelineException;
use PhpPipeline\Processor\Via;
use PHPUnit\Framework\TestCase;
use Test\Fake\Math;
use Test\Fake\MathSecond;

final class ViaTest extends TestCase
{
    /**
     * @dataProvider ProviderForVia
     * @param array<callable> $pipes
     * @param string $via
     * @param mixed $payload
     * @param mixed $expected
     */
    public function testViaSuccess(array $pipes, string $via, $payload, $expected): void
    {
        $pipeline = new Pipeline(new Via($via), ...$pipes);

        self::assertEquals($expected, $pipeline->process($payload));
    }

    /**
     * @return array<string,array<string, array<int, (Closure(int): bool)|(Closure(int): int)>|int|string|true>>
     */
    public function ProviderForVia(): array
    {
        return [
            'multiplePipes' => [
                'pipes' => [
                    new Math(),
                    new MathSecond()
                ],
                'via' => 'multiplication',
                'payload' => 1488,
                'resultAfterPipes' => 1488 * 2 * 3,
            ],
        ];
    }

    /**
     * @return void
     */
    public function testViaClosure(): void
    {
        $processor = new Via('mega_method');
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
        $processor = new Via('not_existing_method');
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
        $processor = new Via('someMethod');
        $payload = 100;
        $pipe = new class() {
            /**
             * @param array<int> $payload
             * @return array<int>
             */
            public function someMethod(array $payload): array
            {
                return $payload;
            }

            /**
             * @param int $payload
             * @return int
             */
            public function __invoke(int $payload): int
            {
                return $payload;
            }
        };

        $processor->passThroughPipes($payload, $pipe);
    }
}