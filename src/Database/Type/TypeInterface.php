<?php
declare(strict_types=1);

namespace Cadre\Framework\Database\Type;

interface TypeInterface
{
    public function __invoke(
        ?string $host,
        ?string $port,
        ?string $database,
        ?string $username,
        ?string $password
    );
}
