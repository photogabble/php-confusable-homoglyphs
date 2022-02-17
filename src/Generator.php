<?php

namespace Photogabble\ConfusableHomoglyphs;
use DateTime;

interface Generator
{
    public function generateFromFile(string $filePathname);
    public function toArray(): array;
    public function toJson(): string;
    public function getDateTime(): DateTime;
}