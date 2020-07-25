<?php declare(strict_types = 1);

namespace Test\Processor;

use Fake;
use PhpPipeline\Pipeline;
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
        $processor = new Via($via);
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
                    new Math(),
                    new MathSecond()
                ],
                'via' => 'multiplication',
                'payload' => 1488,
                'resultAfterPipes' => 1488 * 2 * 3,
            ],
        ];
    }
}