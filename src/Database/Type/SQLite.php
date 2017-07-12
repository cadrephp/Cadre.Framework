<?php
declare(strict_types=1);

namespace Cadre\Framework\Database\Type;

use PDO;

class SQLite implements TypeInterface
{
    public function __invoke(
        ?string $host,
        ?string $port,
        ?string $database,
        ?string $username,
        ?string $password
    ) {
        return $this->getPdo($database);
    }

    private function getPdo(string $database)
    {
        $database = 0 === strcmp($host, 'memory') ? ':memory:' : $database;
        return new PDO(
            "sqlite:{$database}"
        );
    }
}
