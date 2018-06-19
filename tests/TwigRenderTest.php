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
}
