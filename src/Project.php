<?php

/**
 * This file is based on:
 * [Aura\Project_Kernel\Project](https://github.com/auraphp/Aura.Project_Kernel/blob/2f3b3d0612d673a0bfd1446cb895d23aa86d9882/src/Project.php)
 * and
 * [Illuminate\Foundation\PackageManifest](https://github.com/laravel/framework/blob/ffe0c8b6f622be4a147be2aa58e141bea98f71d4/src/Illuminate/Foundation/PackageManifest.php)
 *
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Cadre\Framework;

use Cadre\Framework\Traits\Cacheable;

class Project
{
    use Cacheable;

    private $path;
    private $env;
    private $config;

    public function __construct(string $path, string $env, array $config = [])
    {
        $this->path = $path;
        $this->env = $env;
        $this->config = $config;
    }

    public function getPath($sub = null)
    {
        if ($sub) {
            $sub = ltrim($sub, DIRECTORY_SEPARATOR);
            return $this->path . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $sub);
        }

        return $this->path;
    }

    public function getEnv()
    {
        return $this->env;
    }

    public function getConfig(string $varname)
    {
        if (array_key_exists($varname, $this->config)) {
            return $this->config[$varname];
        }

        $val = getenv($varname);
        if ($val !== false) {
            return $val;
        }

        return null;
    }

    public function getComposer()
    {
        return $this->getCachedValue(
            'cadre-framework.composer',
            [$this, 'readComposer']
        );
    }

    public function getInstalled()
    {
        return $this->getCachedValue(
            'cadre-framework.installed',
            [$this, 'readInstalled']
        );
    }

    public function getManifest()
    {
        return $this->getCachedValue(
            'cadre-framework.manifest',
            [$this, 'buildManifest']
        );
    }

    public function getModules()
    {
        return $this->getCachedValue(
            'cadre-framework.modules',
            [$this, 'collectModules']
        );
    }

    private function readComposer()
    {
        return $this->readFile($this->path . '/composer.json');
    }

    private function readInstalled()
    {
        return $this->readFile($this->path . '/vendor/composer/installed.json');
    }

    private function buildManifest()
    {
        $manifest = [];

        $installed = $this->getInstalled();
        $composer = $this->getComposer();

        foreach ($installed as $package) {
            if (isset($package->extra->cadre)) {
                $manifest[$package->name] = $package->extra->cadre;
            }
        }

        if (isset($composer->extra->cadre)) {
            $this->manifest[$composer->name] = $composer->extra->cadre;
        }

        return $manifest;
    }

    private function collectModules()
    {
        $modules = [];

        $manifest = $this->getManifest();

        foreach ($manifest as $spec) {
            if (isset($spec->modules)) {
                $modules = array_merge($modules, $spec->modules);
            }
        }

        return $modules;
    }

    private function getCachedValue(string $key, callable $builder)
    {
        $value = $this->getCache()->get($key, null);

        if (is_null($value)) {
            $value = call_user_func($builder);
            $this->getCache()->set($key, $value);
        }

        return $value;
    }

    private function readFile($file)
    {
        return json_decode(file_get_contents($file));
    }
}
