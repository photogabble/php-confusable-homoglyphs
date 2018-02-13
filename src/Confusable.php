<?php

namespace Photogabble\ConfusableHomoglyphs;

class Confusable
{

    /**
     * Json decoded content of confusables.json.
     *
     * @var array
     */
    private $confusablesData  = [];

    /**
     * The input text encoding for use with mb_ string functions.
     *
     * @var string
     */
    private $encoding;

    /**
     * @var Categories
     */
    private $categories;

    /**
     * Confusable constructor.
     *
     * Loads the data file containing the confusables information.
     *
     * @param Categories $categories
     * @param string $encoding
     * @param null|string $dataFilePath
     * @throws \Exception
     */
    public function __construct(Categories $categories, string $encoding='utf8', string $dataFilePath = null)
    {
        if (is_null($dataFilePath)){
            $dataFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'categories.json';
        }

        if (!file_exists($dataFilePath)) {
            throw new \Exception('Could not find data file at path ['. $dataFilePath .']');
        }

        $this->confusablesData = json_decode(file_get_contents($dataFilePath), true);
        $this->encoding = $encoding;
        $this->categories = $categories;
    }

    /**
     * Checks if `$string` contains mixed-scripts content, excluding script
     * blocks aliases in `$allowedAliases`.
     *
     * E.g. `B. C` is not considered mixed-scripts by default: it contains characters
     * from **Latin** and **Common**, but **Common** is excluded by default.
     *
     * e.g.
     *
     * isMixedScript('Abc') -> false
     * isMixedScript('ρτ.τ') -> false
     * isMixedScript('ρτ.τ', []) -> true
     * isMixedScript('Alloτ') -> true
     *
     * @param string $string A unicode string
     * @param array $allowedAliases Script blocks aliases not to consider.
     * @return bool Is $string considered mixed-scripts or not.
     */
    public function isMixedScript(string $string, array $allowedAliases = ['COMMON']) : bool
    {
        $allowedAliases = array_map(function($value) {
            return mb_strtoupper($value, $this->encoding);
        }, $allowedAliases);
    }

    /**
     * Checks if `$string` contains characters which might be confusable with
     * characters from `$preferredAliases`.
     *
     * If `$greedy=False`, it will only return the first confusable character
     * found without looking at the rest of the string, `$greedy=True` returns
     * all of them.
     *
     * `$preferredAliases=[]` can take an array of unicode block aliases to be
     * considered as your 'base' unicode blocks:
     *
     * - considering `paρa`,
     *
     *      - with `$preferredAliases=['latin']`, the 3rd character `ρ` would
     *        be returned because this greek letter can be confused with
     *        latin `p`.
     *
     *      - with `$preferredAliases=['greek']`, the 1st character `p` would
     *        would be returned because this latin letter can be confused with
     *        greek `ρ`.
     *
     *      - with `$preferredAliases=[]` and `$greedy=True`, you'll discover
     *        the 29 characters that can be confused with `p`, the 23 characters
     *        that look like `a`, and the one that looks like `ρ` (which is, of
     *        course, *p* aka *LATIN SMALL LETTER P*).
     *
     * e.g.
     *
     * isConfusable('paρa', ['latin'])[0]['character'] -> 'ρ'
     * isConfusable('paρa', ['greek'])[0]['character'] -> 'p'
     * isConfusable('Abç', ['latin']) -> false
     * isConfusable('AlloΓ', ['latin']) -> false
     * isConfusable('ρττ', ['greek']) -> false
     * isConfusable('ρτ.τ', ['greek', 'common']) -> false
     * isConfusable('ρττp') -> ['homoglyphs': ['c': 'p', 'n': 'LATIN SMALL LETTER P'], 'alias': 'GREEK', 'character': 'ρ']
     *
     * @param string $string A unicode string
     * @param bool $greedy Don't stop on finding one confusable character - find all of them.
     * @param array $preferredAliases Script blocks aliases which we don't want `$string`'s characters to be confused with.
     * @return bool|array False if not confusable, all confusable characters and with what they are confusable otherwise.
     */
    public function isConfusable(string $string, bool $greedy = false, array $preferredAliases = [])
    {

    }

    /**
     * Checks if `$string` can be dangerous, i.e. is it not only mixed-scripts
     * but also contains characters from other scripts than the ones in `$preferredAliases`
     * that might be confusable with characters from scripts in `$preferredAliases`.
     *
     * For `$preferredAliases` examples, see `isConfusable` docblock.
     *
     * e.g.
     *
     * isDangerous('Allo') -> false
     * isDangerous('AlloΓ', ['latin]) -> false
     * isDangerous('Alloρ') -> true
     * isDangerous('AlaskaJazz') -> false
     * isDangerous('ΑlaskaJazz') -> true
     *
     * @param string $string A unicode string
     * @param array $preferredAliases Script blocks aliases which we don't want `$string`'s characters to be confused with.
     * @return bool Is it dangerous.
     */
    public function isDangerous(string $string, array $preferredAliases = []) : bool
    {
        return $this->isMixedScript($string) === true && $this->isConfusable($string, $preferredAliases) !== false;
    }

}