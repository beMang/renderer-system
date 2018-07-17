<?php

namespace Test\Extensions;

class TestExtension extends \Twig_Extension {
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('test', [$this, 'test'], ['is_safe' => ['html']])
        ];
    }

    public function test(string $custom) {
        return 'hello test : ' . $custom;
    }
}