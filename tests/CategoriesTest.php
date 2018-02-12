<?php

namespace Photogabble\ConfusableHomoglyphs\Tests;

use Photogabble\ConfusableHomoglyphs\Categories;

class CategoriesTest extends Base
{

    public function testAliasesCategories()
    {
        $categories = new Categories();
        $this->assertEquals($categories->aliasesCategories($this->latinA), [$categories->alias($this->latinA), $categories->category($this->latinA)]);
        $this->assertEquals($categories->aliasesCategories($this->greekA), [$categories->alias($this->greekA), $categories->category($this->greekA)]);
    }

    public function testAlias()
    {
        $categories = new Categories();
        $this->assertEquals('LATIN', $categories->alias($this->latinA));
        $this->assertEquals('GREEK', $categories->alias($this->greekA));
    }

    public function testCategory()
    {
        $categories = new Categories();
        $this->assertEquals('L', $categories->category($this->latinA));
        $this->assertEquals('L', $categories->category($this->greekA));
    }

    public function testUniqueAliases()
    {
        $categories = new Categories();
        $this->assertEquals(['LATIN'], $categories->uniqueAliases($this->isGood));
        $this->assertEquals(['GREEK', 'LATIN'], $categories->uniqueAliases($this->looksGood));
    }
}