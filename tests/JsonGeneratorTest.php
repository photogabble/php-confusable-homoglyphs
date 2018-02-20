<?php

namespace Photogabble\ConfusableHomoglyphs\Tests;

use Photogabble\ConfusableHomoglyphs\Categories;
use Photogabble\ConfusableHomoglyphs\Categories\JsonGenerator;

class JsonGeneratorTest extends Base
{

    public function testCategoriesJsonGenerator()
    {
        $generator = new JsonGenerator();
        try {
            $generator->generateFromFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Scripts.txt');
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

        $arr = $generator->toArray();
        $this->assertSame(['timestamp', 'code_points_ranges', 'categories', 'iso_15924_aliases'], array_keys($arr));

        $this->assertSame([0,31,0,0], $arr['code_points_ranges'][0]);
        $this->assertSame([32,32,0,1], $arr['code_points_ranges'][1]);
        $this->assertSame([33,35,0,2], $arr['code_points_ranges'][2]);
        $this->assertSame([36,36,0,3], $arr['code_points_ranges'][3]);

        $this->assertTrue(count($arr['iso_15924_aliases']) >= 141);
        $this->assertTrue(count($arr['categories']) >= 25);
        $this->assertTrue(count($arr['code_points_ranges']) >= 1963);

        try {
            $json = $generator->toJson();
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Scripts.tmp', $json);

        try {
            $categories = new Categories('utf8',__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Scripts.tmp');
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
            return;
        }

        // Check all tests within Categories Test work with the new file
        $this->assertEquals($categories->aliasesCategories($this->latinA), [$categories->alias($this->latinA), $categories->category($this->latinA)]);
        $this->assertEquals($categories->aliasesCategories($this->greekA), [$categories->alias($this->greekA), $categories->category($this->greekA)]);

        $this->assertEquals('LATIN', $categories->alias($this->latinA));
        $this->assertEquals('GREEK', $categories->alias($this->greekA));

        $this->assertEquals('L', $categories->category($this->latinA));
        $this->assertEquals('L', $categories->category($this->greekA));

        $this->assertEquals(['LATIN'], $categories->uniqueAliases($this->isGood));
        $this->assertEquals(['GREEK', 'LATIN'], $categories->uniqueAliases($this->looksGood));

        unlink(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Scripts.tmp');
    }
}
