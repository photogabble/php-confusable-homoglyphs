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

    public function testAliasesCategories()
    {
        $confusables = $this->getConfusables();
    }

    public function testAlias()
    {
        $confusables = $this->getConfusables();
    }

    public function testCategory()
    {
        $confusables = $this->getConfusables();
    }

    public function testUniqueAliases()
    {
        $confusables = $this->getConfusables();
    }

}