<?php
declare(strict_types=1);

namespace Cadre\Framework\Module;

use Atlas\Orm\AtlasContainer;
use Aura\Di\Container;
use Cadre\Module\Module;

class AtlasOrm extends Module
{
    public function require()
    {
        return [Pdo::class];
    }

    public function define(Container $di)
    {
        /** Services */

        $di->set('atlas:container', $di->lazyNew(AtlasContainer::class));
        $di->set('atlas', $di->lazyGetCall('atlas:container', 'getAtlas'));

        $di->params[AtlasContainer::class] = [
            'dsn' => $di->lazyGet('cadre:pdo'),
        ];

        $pattern = __DIR__ . '/../Persistence/DataSource/*/*Mapper.php';
        $mappers = glob($pattern);
        foreach ($mappers as $i => $file) {
            $mappers[$i] = 'Application\\Persistence\\'
                         . str_replace('/', '\\', substr($file, strpos($file, 'DataSource/'), -4));
        }

        $di->setters[AtlasContainer::class]['setMappers'] = $mappers;
    }
}
