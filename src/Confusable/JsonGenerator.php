<?php

namespace Photogabble\ConfusableHomoglyphs\Confusable;

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
    private $confusablesMatrix = [];



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

        if (preg_match('/[0-9A-F ]+\s+;\s*[0-9A-F ]+\s+;\s*\w+\s*#\*?\s*\( (.+) → (.+) \) (.+) → (.+)\t#/', $line, $matches) < 1) {
            return;
        }

        $charOne = $matches[1];
        $charTwo = $matches[2];
        $nameOne = $matches[3];
        $nameTwo = $matches[4];

        if (! isset($this->confusablesMatrix[$charOne])) {
            $this->confusablesMatrix[$charOne] = [];
        }

        $this->confusablesMatrix[$charOne][] = [
            'c' => $charTwo,
            'n' => $nameTwo
        ];


        if (! isset($this->confusablesMatrix[$charTwo])) {
            $this->confusablesMatrix[$charTwo] = [];
        }

        $this->confusablesMatrix[$charTwo][] = [
            'c' => $charOne,
            'n' => $nameOne
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
        return $this->confusablesMatrix;
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