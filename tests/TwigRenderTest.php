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
    
    public function testDefaulTwigRender()
    {
        $render = new TwigRender(self::BASE_PATH, self::CACHE_PATH);
        $datas = ['title' => 'hello world'];
        $this->assertContains('<h1>' . $datas['title'] . '</h1>', $render->render('test1.twig', $datas));
    }

    public function testCacheTwigRender()
    {
        $render = new TwigRender(self::BASE_PATH, self::CACHE_PATH);
        $datas = ['title' => 'hello world'];
        $this->assertContains('<h1>' . $datas['title'] . '</h1>', $render->render('test1.twig', $datas, true));
    }

    public function testInvalidBasePath()
    {
        $this->expectExceptionMessage('Le chemin de base ou de cache n\'est pas un dossier');
        new TwigRender(uniqid(), self::CACHE_PATH);
    }

    public function testInvalidCachePath()
    {
        $this->expectExceptionMessage('Le chemin de base ou de cache n\'est pas un dossier');
        new TwigRender(self::BASE_PATH, uniqid());
    }

    public function testAddingInvalidExtension()
    {

    }

    public function testAddingExtension()
    {
        $render = new TwigRender(self::BASE_PATH, self::CACHE_PATH);
        $render->addTwigExtensions([\Test\Extensions\TestExtension::class]);
        $data = ['test' => 'how are you ?'];
        $result = $render->render('testExtension.twig', $data);
        $extension = new \Test\Extensions\TestExtension();
        $this->assertEquals($extension->test($data['test']), $result);
    }
}
