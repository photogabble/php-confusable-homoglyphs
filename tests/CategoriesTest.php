<?php

namespace Photogabble\ConfusableHomoglyphs\Tests;

use PHPUnit\Framework\TestCase;

class CategoriesTest extends TestCase
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

    public function testAliasesCategories()
    {
        // @todo
    }

    public function testAlias()
    {
        // @todo
    }

    public function testCategory()
    {
        // @todo
    }

    public function testUniqueAliases()
    {
        // @todo
    }
}