# PhpPipeline

## What is it?

**PhpPipeline** is immutable realisation of same pattern.

## How it works?
Simpliest usage - create new Pipeline with `PassingThroughProcessor` (pass through all your pipes) and pipes:

```php
$pipes = [
    fn(int $payload): int => $payload * 2,
    fn(int $payload): int => $payload * 3,
    new class() { public function __invoke(int $payload): int { return $payload * 4; } }
];

$pipeline = new Pipeline(new PassingThrough(), ...$pipes);
$payload = 100;

$result = $pipeline->process($payload);
```
It is legal to pass objects as pipes, every object `must` implements `__invoke()` method.

## Adding pipes 
New pipes can be appended after instantiation:
```php
$pipes = [
    fn(int $payload): int => $payload * 2,
    fn(int $payload): int => $payload * 3,
];

$pipeline = new Pipeline(new PassingThrough(), ...$pipes);
$pipe = (fn(int $payload): int => $payload * 4);
$pipeline = $pipeline->append($pipe);
$payload = 100;

$result = $pipeline->process($payload);
```

## Nested
Payload will be processed wrapped into functions and processed after. 
```php
$pipes = [
    new class() {
        public function __invoke(int $payload): int { return $payload * 13; }
    },
    new class() {
        public function __invoke(int $payload): int { return $payload * 666; }
    },
    fn(int $payload): int => $payload * 10
];

$pipeline = new Pipeline(new Nested(), ...$pipes);
$payload = 100;

$result = $pipeline->process($payload);
```

### Processors
Processor dictates logic of handling:

Nested - will be wrapper into functions and processed after that
PassingThrough - passes thorough all pipes
InterruptOnTrue - interrupts after pipe return true
InterruptOnFalse - opposite of InterruptOnTrue

