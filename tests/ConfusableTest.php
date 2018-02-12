<?php

namespace Photogabble\ConfusableHomoglyphs\Tests;

use PHPUnit\Framework\TestCase;

class ConfusableTest extends TestCase
{
    private function getConfusables()
    {
        $result = [
            'latinA' => 'A',
            'greekA' => 'Α',
            'isGood'  => 'Allo'
        ];
        $result['looksGood'] = str_replace($result['latinA'], $result['greekA'], $result['isGood']);
        return $result;
    }

    public function testIsMixedScript()
    {
        // @todo
    }

    public function testIsConfusable()
    {
        // @todo
    }

    public  function testIsDangerous()
    {
        // @todo
    }
}