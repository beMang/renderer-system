<?php
namespace Test;

use bemang\Cache\FileCache;
use bemang\renderer\PHPRender;

class PHPRenderTest extends \PHPUnit\Framework\TestCase
{
    const BASE_PATH = __DIR__ . '/views/';
    const CACHE_PATH = __DIR__ . '/cache/';

    public static function setUpBeforeClass() :void
    {
        require_once(__DIR__ . '/../vendor/autoload.php');
    }
    
    public function testDefaultRender()
    {
        $render = new PHPRender(self::BASE_PATH, self::CACHE_PATH);
        $result = $render->render('test1', []);
        $this->assertEquals('<h1>hello world</h1>', $result);
    }

    public function testRenderWithInexistantView()
    {
        $render = new PHPRender(self::BASE_PATH, self::CACHE_PATH);
        $view = uniqid();
        $this->expectExceptionMessage('La vue spécifiée n\'existe pas' . $view);
        $render->render($view, []);
    }

    public function testCacheRenderWithInexistantView()
    {
        $render = new PHPRender(self::BASE_PATH, self::CACHE_PATH);
        $view = uniqid();
        $this->expectExceptionMessage('La vue spécifiée n\'existe pas' . $view);
        $render->render($view, [], true);
    }

    public function testCacheRender()
    {
        $render = new PHPRender(self::BASE_PATH, self::CACHE_PATH);
        $result = $render->render('test2', [], true);
        $cache = new FileCache(self::CACHE_PATH);
        $this->assertEquals($cache->get(PHPRender::CACHE_NAME . 'test2'), $result);
        $result = $render->render('test2', [], true);
        $this->assertEquals($cache->get(PHPRender::CACHE_NAME . 'test2'), $result);
        $result = $render->render('test2', [], true);
        $this->assertEquals($cache->get(PHPRender::CACHE_NAME . 'test2'), $result);
    }
}
