<?php

namespace Photogabble\ConfusableHomoglyphs\Tests;

use Photogabble\ConfusableHomoglyphs\Categories;
use Photogabble\ConfusableHomoglyphs\Confusable;

class ConfusableTest extends Base
{
    public function testIsMixedScript()
    {
        try {
            $categories = new Categories();
            $confusables = new Confusable($categories);
        }catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

        $this->assertTrue($confusables->isMixedScript($this->looksGood));
        $this->assertTrue($confusables->isMixedScript(' ρττ a'));

        $this->assertFalse($confusables->isMixedScript($this->looksGood, ['GREEK', 'LATIN']));
        $this->assertFalse($confusables->isMixedScript($this->isGood));
        $this->assertFalse($confusables->isMixedScript('ρτ.τ'));
        $this->assertFalse($confusables->isMixedScript('ρτ.τ '));
    }

    public function testIsConfusable()
    {
        try {
            $categories = new Categories();
            $confusables = new Confusable($categories);
        }catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

        $greek = $confusables->isConfusable($this->looksGood, false, ['latin']);
        $this->assertEquals($this->greekA, $greek[0]['character']);
        $this->assertEquals([
            [
                'c' => 'A',
                'n' => 'LATIN CAPITAL LETTER A'
            ]
        ], $greek[0]['homoglyphs']);

        $latin = $confusables->isConfusable($this->isGood, false, ['latin']);
        $this->assertFalse($latin);

        $this->assertFalse($confusables->isConfusable('AlloΓ', false, ['latin']));

        // Stop at first confusable character
        $this->assertEquals(1, count($confusables->isConfusable('Αlloρ', false)));

        // Find all confusable characters
        // Α (greek), l, o, and ρ can be confused with other unicode characters
        $this->assertEquals(4, count($confusables->isConfusable('Αlloρ', true)));

        // Only Α (greek) and ρ (greek) can be confused with a latin character
        $this->assertEquals(2, count($confusables->isConfusable('Αlloρ', true, ['latin'])));

        // For 'Latin' readers, ρ is confusable!    ↓
        $confusable = $confusables->isConfusable('paρa', false, ['latin'])[0]['character'];
        $this->assertEquals('ρ', $confusable);

        // For 'Greek' readers, p is confusable!  ↓
        $confusable = $confusables->isConfusable('paρa', false, ['greek'])[0]['character'];
        $this->assertEquals('p', $confusable);

        // Microsoft contains a zero width character - added for #2
        $this->assertTrue(is_array($confusables->isConfusable('www.micros﻿оft.com')));
        $this->assertTrue(is_array($confusables->isConfusable('www.Αpple.com')));
        $this->assertTrue(is_array($confusables->isConfusable('www.faϲebook.com')));

        // The three below are all not confusable - added for #2
        $this->assertFalse(is_array($confusables->isConfusable('www.microsoft.com', false, ['latin'])));
        $this->assertFalse(is_array($confusables->isConfusable('www.apple.com', false, ['latin'])));
        $this->assertFalse(is_array($confusables->isConfusable('www.facebook.com', false, ['latin'])));
    }

    public function testIsDangerous()
    {
        try {
            $categories = new Categories();
            $confusables = new Confusable($categories);
        }catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

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