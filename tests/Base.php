<?php

namespace Photogabble\ConfusableHomoglyphs\Tests;

use PHPUnit\Framework\TestCase;

class Base extends TestCase
{
    protected $latinA = 'A';
    protected $greekA = 'Α';
    protected $isGood = 'Allo';
    protected $looksGood;

    protected function setUp()
    {
        parent::setUp();
        $this->looksGood = str_replace($this->latinA, $this->greekA, $this->isGood);
    }
}