<?php

namespace bemang\renderer;

use bemang\Cache\FileCache;
use bemang\renderer\Exception\RendererException;
use bemang\renderer\Exception\InvalidArgumentException;

class PHPRender implements RendererInterface
{
    protected $basePath = '';
    protected $cachePath = '';
    const CACHE_NAME = 'bemang_renderer_system_cache';

    public function __construct(string $basePath, string $cachePath)
    {
        if ($this->checkPath($basePath) === true &&
            $this->checkPath($cachePath) === true
        ) {
            $this->basePath = $basePath;
            $this->cachePath = $cachePath;
        } else {
            throw new InvalidArgumentException('Le chemin de base ou de cache n\'est pas un dossier');
        }
    }

    public function render(string $view, array $datas, bool $cache = false)
    {
        if ($cache === false) {
            return $this->classicRender($view, $datas);
        } else {
            return $this->cacheRender($view, $datas);
        }
    }

    protected function classicRender(string $view, array $datas) :string
    {
        $fileToRender = $this->basePath . $view . '.php';
        if (!file_exists($fileToRender)) {
            throw new RendererException('La vue spécifiée n\'existe pas' . $view);
        }
        extract($datas);
        ob_start();
        require($fileToRender);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    protected function cacheRender(string $view, array $datas) :string
    {
        $cache = new FileCache($this->cachePath);
        if ($cache->has(self::CACHE_NAME . $view) === true) {
            return $cache->get(self::CACHE_NAME . $view);
        } else {
            $fileToRender = $this->basePath . $view . '.php';
            if (!file_exists($fileToRender)) {
                throw new \RuntimeException('La vue spécifiée n\'existe pas' . $view);
            }
            ob_start();
            extract($datas);
            require $fileToRender;
            $content = ob_get_contents();
            ob_end_clean();
            $cache->set(self::CACHE_NAME . $view, $content);
            return $content;
        }
    }

    private function checkPath(string $basePath) :bool
    {
        if (is_dir($basePath)) {
            return true;
        } else {
            return false;
        }
    }
}
