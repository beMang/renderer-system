<?php
namespace Test;

use bemang\Cache\FileCache;
use bemang\renderer\TwigRender;

class TwigRenderTest extends \PHPUnit\Framework\TestCase
{
    const BASE_PATH = __DIR__ . '/views/';
    const CACHE_PATH = __DIR__ . '/cache/';

    public static function setUpBeforeClass() :void
    {
        require_once(__DIR__ . '/../vendor/autoload.php');
    }
    
    public function testDefaultRender()
    {
        $render = new TwigRender(self::BASE_PATH, self::CACHE_PATH);
        $result = $render->render('test1.php', []);
        $this->assertEquals('<h1>hello world</h1>', $result);
    }

    public function testExceptionForWrongPath()
    {
        $this->expectExceptionMessage('Le chemin de base ou de cache n\'est pas un dossier');
        $render = new TwigRender('wrong_path', self::CACHE_PATH);
        $this->expectExceptionMessage('Le chemin de base ou de cache n\'est pas un dossier');
        $render = new TwigRender(self::BASE_PATH, 'wrong_path');
    }

    public function testRenderWithInexistantView()
    {
        $render = new TwigRender(self::BASE_PATH, self::CACHE_PATH);
        $view = uniqid();
        $this->expectException(\Twig\Error\Error::class);
        $render->render($view, []);
    }

    public function testCacheRender()
    {
        $render = new TwigRender(self::BASE_PATH, self::CACHE_PATH);
        $result = $render->render('test1.php', [], true);
        $this->assertEquals('<h1>hello world</h1>', $result);
    }

    public function testExtension()
    {
        $render = new TwigRender(self::BASE_PATH, self::CACHE_PATH);
        $render->addTwigExtensions([TwigExtension::class]);
        $this->assertEquals('Extension is working', $render->render('test2.php', []));

        $render = new TwigRender(self::BASE_PATH, self::CACHE_PATH);
        $render->addTwigExtensions([TwigExtension::class]);
        $this->assertEquals('Extension is working', $render->render('test2.php', [], true));
    }

    public function testInvalidExtension()
    {
        $render = new TwigRender(self::BASE_PATH, self::CACHE_PATH);
        $this->expectExceptionMessage('Une extension est invalide');
        $render->addTwigExtensions([TwigRender::class]);
    }
}
