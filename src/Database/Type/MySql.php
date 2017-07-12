<?php
declare(strict_types=1);

namespace Cadre\Framework\Database\Type;

use PDO;

class MySql implements TypeInterface
{
    public function __invoke(
        ?string $host,
        ?string $port,
        ?string $database,
        ?string $username,
        ?string $password
    ) {
        return $this->getPdo($host, $database, $username, $password, $port);
    }

    private function getPdo(
        string $host,
        string $database,
        string $username,
        string $password,
        string $port = '3306'
    ) {
        $port = empty($port) ? '3306' : $port;
        $dsn = "mysql:host={$host};port={$port};dbname={$database}";
        return new PDO($dsn, $username, $password);
    }
}
