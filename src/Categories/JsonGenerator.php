<?php

namespace Photogabble\ConfusableHomoglyphs\Categories;

use Exception;

class JsonGenerator
{

    private $sourceDatestamp;
    private $codePointsRanges = [];
    private $iso15924Aliases = [];
    private $categories = [];

    /**
     * Generates the categories JSON data file from the unicode specification
     * loaded from the given `$filePathname` string.
     *
     * @param string $filePathname
     * @return true
     * @throws Exception
     */
    public function generateFromFile(string $filePathname) : bool
    {
        if (!file_exists($filePathname)){
            throw new Exception('The file found at ['.$filePathname.'] could not be read.');
        }
        $handle = fopen($filePathname, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $this->parseLine($line);
            }

            fclose($handle);
        } else {
            throw new Exception('The file found at ['.$filePathname.'] could not be opened.');
        }


        return true;
    }

    /**
     * Generates the categories JSON data file from the unicode specification
     * loaded from the given `$url`.
     *
     * @param string $url
     * @return true
     */
    public function generateFromUrl(string $url) : bool
    {
        return true;
    }

    private function parseLine(string $line) : void
    {
        if (preg_match('/Date: ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])), ((?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)) ([A-Z]+)/', $line, $dateMatches) > 0) {
            $this->sourceDatestamp = new \DateTime($dateMatches[1] . ' ' . $dateMatches[4], new \DateTimeZone($dateMatches[8]));
            return;
        } unset($dateMatches);

        if (preg_match('/([0-9A-F]+)(?:\.\.([0-9A-F]+))?\W+(\w+)\s*#\s*(\w+)/', $line, $matches) < 1) {
            return;
        }

        $code_point_range_from = $matches[1];
        $code_point_range_to = $matches[2];
        $alias = mb_strtoupper($matches[3]);
        $category = $matches[4];

        if (! in_array($alias, $this->iso15924Aliases)){
            $this->iso15924Aliases[] = $alias;
        }

        if (! in_array($category, $this->categories)){
            $this->categories[] = $category;
        }

        $this->codePointsRanges[] = [
            hexdec($code_point_range_from),
            hexdec($code_point_range_to),
            array_search($alias, $this->iso15924Aliases, true),
            array_search($category, $this->categories, true)
        ];
    }
}