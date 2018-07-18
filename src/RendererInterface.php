<?php

namespace bemang\renderer;

interface RendererInterface
{
    public function __construct(string $basePath, string $cachePath);
    public function render(string $view, array $data, bool $cache = false);
}
