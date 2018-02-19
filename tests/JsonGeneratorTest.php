<?php

namespace Photogabble\ConfusableHomoglyphs\Tests;

use Photogabble\ConfusableHomoglyphs\Categories\JsonGenerator;

class JsonGeneratorTest extends Base
{

    public function testCategoriesJsonGenerator()
    {

        $generator = new JsonGenerator();
        $generator->generateFromFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Scripts.txt');

        // @todo
    }

}