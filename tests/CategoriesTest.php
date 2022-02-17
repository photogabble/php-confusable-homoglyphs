<?php

namespace Photogabble\ConfusableHomoglyphs\Tests;

use Exception;
use Photogabble\ConfusableHomoglyphs\Categories;
use Photogabble\ConfusableHomoglyphs\Categories\JsonGenerator;

class CategoriesTest extends Base
{

    /**
     * Get Scripts.txt from the unicode.org website.
     */
    public static function setUpBeforeClass()
    {
        if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Scripts.txt')) {
            $scripts = file_get_contents('https://www.unicode.org/Public/UNIDATA/Scripts.txt');
            file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Scripts.txt', $scripts);
        }
    }

    /**
     * @return Categories
     */
    private function categoriesFactory(): Categories
    {
        try {
            return new Categories('utf8', __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'categories.json');
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testCategoriesJsonGenerator()
    {
        $generator = new JsonGenerator();
        try {
            $generator->generateFromFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'Scripts.txt');
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        $arr = $generator->toArray();
        $this->assertSame(['timestamp', 'code_points_ranges', 'categories', 'iso_15924_aliases'], array_keys($arr));

        $this->assertSame([0, 31, 0, 0], $arr['code_points_ranges'][0]);
        $this->assertSame([32, 32, 0, 1], $arr['code_points_ranges'][1]);
        $this->assertSame([33, 35, 0, 2], $arr['code_points_ranges'][2]);
        $this->assertSame([36, 36, 0, 3], $arr['code_points_ranges'][3]);

        $this->assertTrue(count($arr['iso_15924_aliases']) >= 141);
        $this->assertTrue(count($arr['categories']) >= 25);
        $this->assertTrue(count($arr['code_points_ranges']) >= 1963);

        try {
            $json = $generator->toJson();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'categories.json', $json);
    }

    public function testAliasesCategories()
    {
        $categories = $this->categoriesFactory();

        $this->assertEquals($categories->aliasesCategories($this->latinA), [$categories->alias($this->latinA), $categories->category($this->latinA)]);
        $this->assertEquals($categories->aliasesCategories($this->greekA), [$categories->alias($this->greekA), $categories->category($this->greekA)]);
    }

    public function testAlias()
    {
        $categories = $this->categoriesFactory();

        $this->assertEquals('LATIN', $categories->alias($this->latinA));
        $this->assertEquals('GREEK', $categories->alias($this->greekA));
    }

    public function testCategory()
    {
        $categories = $this->categoriesFactory();

        $this->assertEquals('L', $categories->category($this->latinA));
        $this->assertEquals('L', $categories->category($this->greekA));
    }

    public function testUniqueAliases()
    {
        $categories = $this->categoriesFactory();

        $this->assertEquals(['LATIN'], $categories->uniqueAliases($this->isGood));
        $this->assertEquals(['GREEK', 'LATIN'], $categories->uniqueAliases($this->looksGood));
    }
}