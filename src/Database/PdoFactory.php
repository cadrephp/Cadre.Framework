<?php
declare(strict_types=1);

namespace Cadre\Framework\Database;

use Exception;

class PdoFactory
{
    private $types = [];

    public function __construct(array $types)
    {
        $this->types = $types;
    }

    public function __invoke(
        ?string $type,
        ?string $host,
        ?string $port,
        ?string $database,
        ?string $username,
        ?string $password
    ) {
        if (array_key_exists($type, $this->types) && is_callable($this->types[$type])) {
            $conn = call_user_func(
                $this->types[$type],
                $host,
                $port,
                $database,
                $username,
                $password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } else {
            throw new Exception("No valid type defined for {$type}");
        }
    }
}
