<?php

namespace Photogabble\ConfusableHomoglyphs;

class Categories
{

    private $categoriesData = [];

    /**
     * Categories constructor.
     *
     * Loads the data file containing the categories information.
     *
     * @param null|string $dataFilePath
     * @throws \Exception
     */
    public function __construct($dataFilePath = null)
    {
        if (is_null($dataFilePath)){
            $dataFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'categories.json';
        }

        if (!file_exists($dataFilePath)) {
            throw new \Exception('Could not find data file at path ['. $dataFilePath .']');
        }

        $this->categoriesData = json_decode(file_get_contents($dataFilePath), true);
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
    public function aliasesCategories($chr)
    {
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
    public function alias($chr)
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
    public function category($chr)
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
    public function uniqueAliases($string)
    {

    }

}