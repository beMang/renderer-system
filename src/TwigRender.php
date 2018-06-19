<?php

namespace bemang\renderer;

use bemang\renderer\Exception\InvalidArgumentException;

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
        $loaderTwig = new \Twig_Loader_Filesystem($this->basePath);
        $twig = new \Twig_Environment($loaderTwig, [
            'cache' => false
        ]);
        foreach ($this->twigExtensions as $extension) {
            $twig->addExtension(new $extension());
        }
        return $twig->render($view, $datas);
    }

    protected function cacheTwigRender($view, $datas)
    {
        $loaderTwig = new \Twig_Loader_Filesystem($this->basePath);
        $twig = new \Twig_Environment($loaderTwig, [
            'cache' => $this->cachePath
        ]);
        foreach ($this->twigExtensions as $extension) {
            $twig->addExtension(new $extension);
        }
        return $twig->render($view, $datas);
    }

    public function addExtensions(array $extensions)
    {
        if ($this->checkExtensions($extensions) === true) {
            $this->twigExtensions = array_merge($extensions, $twigExtensions);
        } else {
            throw new InvalidArgumentException('Une extension est invalide');
        }
    }

    private function checkExtensions(array $extensions) :bool
    {
        $result = true;
        foreach ($extensions as $extension) {
            if (!class_exists($extension) || !$extension instanceof \Twig_Extension) {
                $result = false;
            }
        }
        return $result;
    }

    public function getExtensions() : array
    {
        return $this->twigExtensions;
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
