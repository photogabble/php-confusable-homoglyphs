<?php

namespace Photogabble\ConfusableHomoglyphs\Categories;

use Exception;

class JsonGenerator
{

    /**
     * @var \DateTime
     */
    private $sourceDatetime;

    /**
     * @var array
     */
    private $codePointsRanges = [];

    /**
     * @var array
     */
    private $iso15924Aliases = [];

    /**
     * @var array
     */
    private $categories = [];

    /**
     * Generates the categories JSON data file from the unicode specification
     * loaded from the given `$filePathname` string.
     *
     * @param string $filePathname
     * @throws Exception
     */
    public function generateFromFile(string $filePathname) : void
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

        sort($this->codePointsRanges);
        return;
    }

    /**
     * Parse the given $line into code point range's, alias and category.
     *
     * @param string $line
     */
    private function parseLine(string $line) : void
    {
        if (preg_match('/Date: ([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])), ((?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)) ([A-Z]+)/', $line, $dateMatches) > 0) {
            $this->sourceDatetime = new \DateTime($dateMatches[1] . ' ' . $dateMatches[4], new \DateTimeZone($dateMatches[8]));
            return;
        } unset($dateMatches);

        if (preg_match('/([0-9A-F]+)(?:\.\.([0-9A-F]+))?\W+(\w+)\s*#\s*(\w+)/', $line, $matches) < 1) {
            return;
        }

        $codePointRangeFrom = $matches[1];
        $codePointRangeTo = $matches[2];
        $alias = mb_strtoupper($matches[3]);
        $category = $matches[4];

        if (! in_array($alias, $this->iso15924Aliases)){
            $this->iso15924Aliases[] = $alias;
        }

        if (! in_array($category, $this->categories)){
            $this->categories[] = $category;
        }

        $this->codePointsRanges[] = [
            hexdec($codePointRangeFrom),
            hexdec((empty($codePointRangeTo) ? $codePointRangeFrom : $codePointRangeTo)),
            array_search($alias, $this->iso15924Aliases, true),
            array_search($category, $this->categories, true)
        ];
        return;
    }

    /**
     * Return categories data as an array.
     *
     * @return array
     */
    public function toArray() : array
    {
        return [
            'timestamp' => $this->sourceDatetime->format('c'),
            'code_points_ranges' => $this->codePointsRanges,
            'categories' => $this->categories,
            'iso_15924_aliases' => $this->iso15924Aliases
        ];
    }

    /**
     * Return categories data as a json string.
     *
     * @return string
     * @throws Exception
     */
    public function toJson() : string
    {
        $json = json_encode($this->toArray());
        if ($json === false) {
            throw new Exception(json_last_error_msg(), json_last_error());
        }
        return $json;
    }
}