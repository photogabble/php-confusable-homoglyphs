<?php

namespace Photogabble\ConfusableHomoglyphs\Tests;

use Exception;
use Photogabble\ConfusableHomoglyphs\Categories;
use Photogabble\ConfusableHomoglyphs\Generators\CategoriesJsonGenerator;

class CategoriesTest extends Base
{
    /**
     * @return Categories
     */
    private function categoriesFactory(): Categories
    {
        try {
            return new Categories('utf8', __DIR__ . '/../src/categories.json');
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testCategoriesJsonGenerator()
    {
        $generator = new CategoriesJsonGenerator();
        try {
            $generator->generateFromFile(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'categories.txt');
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
            $generator->toJson();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
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