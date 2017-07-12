<?php
declare(strict_types=1);

namespace Cadre\Framework\Database\Type;

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
        $host = 0 === strcmp($host, 'localhost') ? '127.0.0.1' : $host;
        $port = empty($port) ? '3306' : $port;
        return new PDO(
            "sqlite:{$database}"
        );
    }
}
