<?php

namespace Test;

require_once(__DIR__ . '/../vendor/autoload.php');

class TwigExtension extends \Twig\Extension\AbstractExtension
{
    public function getFunctions()
    {
        return [new \Twig\TwigFunction('test', [$this, 'test'], ['is_safe' => ['html']])];
    }

    public function test()
    {
        return 'Extension is working';
    }
}
