<?php declare(strict_types = 1);

namespace PhpPipeline\Result;

final class ByDefaultFactory implements FactoryInterface
{

    /**
     * @param mixed $payload
     * @return ByDefault
     */
    public function createResult($payload): ByDefault
    {
        return new ByDefault($payload);
    }
}