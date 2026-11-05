<?php

declare(strict_types=1);

namespace Xakki\Log\Php\contract;

interface CacheInterface
{
    public function get(string $key): mixed;

    public function set(string $key, mixed $value, ?int $duration = null): void;
}
