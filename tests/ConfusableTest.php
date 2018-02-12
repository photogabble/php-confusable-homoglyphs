<?php

namespace Photogabble\ConfusableHomoglyphs\Tests;

use Photogabble\ConfusableHomoglyphs\Confusable;

class ConfusableTest extends Base
{
    public function testIsMixedScript()
    {
        $confusables = new Confusable();
        $this->assertTrue($confusables->isMixedScript($this->looksGood));
        $this->assertTrue($confusables->isMixedScript(' ρττ a'));

        $this->assertFalse($confusables->isMixedScript($this->isGood));
        $this->assertFalse($confusables->isMixedScript('ρτ.τ'));
        $this->assertFalse($confusables->isMixedScript('ρτ.τ '));
    }

    public function testIsConfusable()
    {
        // @todo
    }

    public  function testIsDangerous()
    {
        $confusables = new Confusable();
        
        $this->assertTrue($confusables->isDangerous($this->looksGood));
        $this->assertTrue($confusables->isDangerous(' ρττ a'));
        $this->assertTrue($confusables->isDangerous('ρττ a'));
        $this->assertTrue($confusables->isDangerous('Alloτ'));
        $this->assertTrue($confusables->isDangerous('www.micros﻿оft.com'));
        $this->assertTrue($confusables->isDangerous('www.Αpple.com'));
        $this->assertTrue($confusables->isDangerous('www.faϲebook.com'));
        $this->assertFalse($confusables->isDangerous('AlloΓ', ['latin']));
        $this->assertFalse($confusables->isDangerous($this->isGood));
        $this->assertFalse($confusables->isDangerous(' ρτ.τ'));
        $this->assertFalse($confusables->isDangerous('ρτ.τ'));
    }
}