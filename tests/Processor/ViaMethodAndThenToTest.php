<?php declare(strict_types = 1);

namespace Test\Processor;

use PhpPipeline\Pipeline;
use PhpPipeline\Processor\ViaMethodAndThenTo;
use Test\Fake\Greet;
use PHPUnit\Framework\TestCase;

final class ViaMethodAndThenToTest extends TestCase
{
    /**
     * @dataProvider ProviderForViaMethodAndThenTo
     * @param array<callable> $pipes
     * @param mixed $payload
     * @param Greet $expected
     */
    public function testViaMethodAndThenToSuccess(array $pipes, $payload, Greet $expected): void
    {
        $processor = new ViaMethodAndThenTo(fn(string $greet) => new Greet($greet),'concat');
        $pipeline = new Pipeline($processor, ...$pipes);

        self::assertEquals($expected->printGreet(), ($pipeline->send($payload))->printGreet());
    }

    /**
     * // phpstan brokes on `class` in returning types
     * @return array
     */
    public function ProviderForViaMethodAndThenTo(): array
    {
        return [
            'multiplePipes' => [
                'pipes' => [
                    new class() {
                        public function concat(string $payload): string
                        {
                            return sprintf("%s guys", $payload);
                        }
                        public function __invoke(string $payload): string
                        {
                            return $payload;
                        }
                    },
                    new class() {
                        public  function concat(string $payload): string
                        {
                            return sprintf("%s how are", $payload);
                        }
                        public function __invoke(string $payload): string
                        {
                            return $payload;
                        }
                    },
                    new class() {
                        public  function concat(string $payload): string
                        {
                            return sprintf("%s you?!", $payload);
                        }
                        public function __invoke(string $payload): string
                        {
                            return $payload;
                        }
                    }
                ],
                'payload' => "Hello",
                'resultAfterPipes' => new Greet("Hello guys how are you?!"),
            ],
        ];
    }
}