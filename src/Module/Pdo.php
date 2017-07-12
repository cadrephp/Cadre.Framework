<?php
declare(strict_types=1);

namespace Cadre\Framework\Module;

use Aura\Di\Container;
use Cadre\Framework\Database\Type\MySql;
use Cadre\Framework\Database\Type\SQLite;
use Cadre\Framework\Database\PdoFactory;
use Cadre\Module\Module;

class Pdo extends Module
{
    public function define(Container $di)
    {
        /** Services */

        $di->set('cadre:pdo/factory', $di->lazyNew(PdoFactory::class));
        $di->set(
            'cadre:pdo',
            $di->lazyGetCall(
                'cadre:pdo/factory',
                '__invoke',
                getenv('DB_CONNECTION'),
                getenv('DB_HOST'),
                getenv('DB_PORT'),
                getenv('DB_DATABASE'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD')
            )
        );

        /** PdoFactory */

        $di->params[PdoFactory::class] = [
            'types' => $di->lazyArray([
                'mysql' => $di->lazyNew(MySql::class),
                'sqlite' => $di->lazyNew(SQLite::class),
            ]),
        ];
    }
}
