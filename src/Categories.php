<?php

namespace Photogabble\ConfusableHomoglyphs;

class Categories
{
    /**
     * Json decoded content of categories.json.
     *
     * @var array
     */
    private $categoriesData = [];

    /**
     * The input text encoding for use with mb_ string functions.
     *
     * @var string
     */
    private $encoding;

    /**
     * Categories constructor.
     *
     * Loads the data file containing the categories information.
     *
     * @param string $encoding
     * @param null|string $dataFilePath
     * @throws \Exception
     */
    public function __construct(string $encoding='utf8', string $dataFilePath = null)
    {
        if (is_null($dataFilePath)){
            $dataFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'categories.json';
        }

        if (!file_exists($dataFilePath)) {
            throw new \Exception('Could not find data file at path ['. $dataFilePath .']');
        }

        $this->categoriesData = json_decode(file_get_contents($dataFilePath), true);
        $this->encoding = $encoding;
    }

    /**
     * Retrieves the script block alias and unicode category for a unicode character.
     *
     * e.g.
     *
     * aliasesCategories('A') -> ['LATIN', 'L']
     * aliasesCategories('τ') -> ['GREEK', 'L']
     * aliasesCategories('-') -> ['COMMON', 'Pd']
     *
     * @param string $chr A unicode character
     * @return array The script block alias and unicode category for a unicode character.
     */
    public function aliasesCategories(string $chr) : array
    {
        $l = 0;
        $r = count($this->categoriesData['code_points_ranges']);
        $c = mb_ord($chr, $this->encoding);

        // Binary Search
        while ($r >= $l){
            $m = floor(($l + $r) / 2);
            if ($c < $this->categoriesData['code_points_ranges'][$m][0]) {
                $r = $m - 1;
            } else if ($c > $this->categoriesData['code_points_ranges'][$m][1]) {
                $l = $m + 1;
            } else {
                return [
                    $this->categoriesData['iso_15924_aliases'][$this->categoriesData['code_points_ranges'][$m][2]],
                    $this->categoriesData['categories'][$this->categoriesData['code_points_ranges'][$m][3]],
                ];
            }
        }

        return ['Unknown', 'Zzzz'];
    }

    /**
     * Retrieves the script block alias for a unicode character.
     *
     * e.g
     *
     * alias('A') -> 'LATIN'
     * alias('τ') -> 'GREEK'
     * alias('-') -> 'COMMON'
     *
     * @param string $chr A unicode character
     * @return string The script block alias.
     */
    public function alias(string $chr) : string
    {
        $arr = $this->aliasesCategories($chr);
        return $arr[0];
    }

    /**
     * Retrieves the unicode category for a unicode character.
     *
     * e.g.
     *
     * category('A') -> 'L'
     * category('τ') -> 'L'
     * category('-') -> 'Pd'
     *
     * @param string $chr A unicode character
     * @return string The unicode category for a unicode character.
     */
    public function category(string $chr) : string
    {
        $arr = $this->aliasesCategories($chr);
        return $arr[1];
    }

    /**
     * Retrieves all unique script block aliases used in a unicode string.
     *
     * e.g.
     *
     * uniqueAliases('ABC') -> ['LATIN']
     * uniqueAliases('ρAτ-') -> ['GREEK', 'LATIN', 'COMMON']
     *
     * @param string $string A unicode string
     * @return array A set of the script block aliases used in a unicode string.
     */
    public function uniqueAliases(string $string) : array
    {
        $return = [];
        foreach (preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY) as $char) {
            $alias = $this->alias($char);
            if (! in_array($alias, $return)) {
                $return[] = $alias;
            }
        }
        return $return;
    }
}