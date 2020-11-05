<?php

namespace bemang\renderer;

use bemang\renderer\Exception\InvalidArgumentException;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class TwigRender implements RendererInterface
{
    protected $basePath = '';
    protected $cachePath = '';
    protected $twigExtensions = [];

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
            return $this->classicTwigRender($view, $datas);
        } else {
            return $this->cacheTwigRender($view, $datas);
        }
    }

    protected function classicTwigRender(string $view, array $datas)
    {
        $twigLoader = new FilesystemLoader($this->basePath);
        $twig = new Environment($twigLoader, [
            'cache' => false
        ]);
        foreach ($this->twigExtensions as $extension) {
            $twig->addExtension(new $extension());
        }
        return $twig->render($view, $datas);
    }

    protected function cacheTwigRender($view, $datas)
    {
        $twigLoader = new FilesystemLoader($this->basePath);
        $twig = new Environment($twigLoader, [
            'cache' => $this->cachePath
        ]);
        foreach ($this->twigExtensions as $extension) {
            $twig->addExtension(new $extension);
        }
        return $twig->render($view, $datas);
    }

    public function addTwigExtensions(array $extensions)
    {
        if ($this->checkExtensions($extensions) === true) {
            $this->twigExtensions = array_merge($extensions, $this->twigExtensions);
        } else {
            throw new InvalidArgumentException('Une extension est invalide');
        }
    }

    protected function checkExtensions(array $extensions) :bool
    {
        foreach ($extensions as $extension) {
            if ($this->checkOneExtension($extension) === false) {
                return false;
            }
        }
        return true;
    }

    protected function checkOneExtension(string $extension) :bool
    {
        if (!class_exists($extension)) {
            return false;
        } else {
            $reflection = new \ReflectionClass($extension);
            return $reflection->isSubclassOf(\Twig\Extension\AbstractExtension::class);
        }
    }

    public function getExtensions() : array
    {
        return $this->twigExtensions;
    }

    protected function checkPath(string $basePath) :bool
    {
        if (is_dir($basePath)) {
            return true;
        } else {
            return false;
        }
    }
}
