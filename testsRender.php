<?php

use bemang\renderer\PHPRender;
use bemang\renderer\TwigRender;

require('vendor/autoload.php');

const BASE_PATH = __DIR__ . '/tests/views/';
const CACHE_PATH = __DIR__ . '/tests/cache/';

$render = new PHPRender(BASE_PATH, CACHE_PATH);
echo $render->render('test1', []);
