# PhpPipeline

## What is it?

**PhpPipeline** is realisation of same pattern.

## How it works?
Simpliest usage - create new pipeline with `PassingThroughProcessor` (pass through all your pipes) and pipes:

```php
$pipes = [
    fn(int $payload): int => $payload * 2,
    fn(int $payload): int => $payload * 3,
    new class() { public function __invoke(int $payload): int { return $payload * 4; } }
];

$pipeline = new Pipeline(new PipelineConfig(), ...$pipes);
$payload = 100;

$result = $pipeline->resultOf($payload)->thenReturn();
```
It is legal to pass objects as pipes, every object `must` implements `__invoke()` method.

## Adding pipes 
New pipes can be added or appended after instantiation:
```php
$pipes = [
    fn(int $payload): int => $payload * 2,
    fn(int $payload): int => $payload * 3,
];

$pipeline = new Pipeline(new PipelineConfig(), ...$pipes);
$pipe = (fn(int $payload): int => $payload * 2);
$pipeline = $pipeline->append($pipe);
$payload = 100;

$result = $pipeline->resultOf($payload)->thenReturn();
```


## Process result
Result can be processed inside pipeline:
```php
$pipeline = new Pipeline(new PipelineConfig(), (fn(int $payload): int => $payload * 2));
$handleFunc = fn($payload) => ['success' => true, 'result' => [0 => $payload]];
$payload = 100;

$result = $pipeline->resultOf($payload)->then($handleFunc);
```

## Via
Payload will be processed via custom method. 
If such method doesn't exit - via `__invoke()` method.
Lamba funcs will be processed in usual way
```php
$pipes = [
    new class() {
        public function multiplicate(int $payload): int { return $payload * 4; }
        public function __invoke(int $payload): int { return $payload * 4; }
    },
    new class() {
        public function __invoke(int $payload): int { return $payload * 4; }
    },
    fn(int $payload): int => $payload * 10
];

$config = new PipelineConfig(new Via('multiplicate'));
$pipeline = new Pipeline($config, ...$pipes);
$payload = 100;

$result = $pipeline->resultOf($payload)->thenReturn();
```

### Processors
Processor dictates logic of handling:

Via - passes thorough all pipes via custom method

PassingThrough - passes thorough all pipes

InterruptOnTrue - interruts after pipe return true

InterruptOnFalse - opposite of previous

