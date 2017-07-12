<?php
declare(strict_types=1);

namespace Cadre\Framework\Traits;

use Cache\Adapter\Void\VoidCachePool;
use Psr\SimpleCache\CacheInterface;

trait Cacheable
{
    private $cache;

    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getCache()
    {
        if (empty($this->cache)) {
            $this->cache = new VoidCachePool();
        }

        return $this->cache;
    }
}
