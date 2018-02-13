<?php

/**
 * Polyfill for mb_ord on PHP versions < 7.2
 */
if (!function_exists('mb_ord')) {
    /**
     * @author John Slegers
     * @see https://stackoverflow.com/a/24763271/1225977
     * @param string $char
     * @param string $encoding
     * @return mixed
     */
    function mb_ord($char, $encoding = 'UTF-8') {
        if ($encoding === 'UCS-4BE') {
            list(, $ord) = (strlen($char) === 4) ? @unpack('N', $char) : @unpack('n', $char);
            return $ord;
        } else {
            return mb_ord(mb_convert_encoding($char, 'UCS-4BE', $encoding), 'UCS-4BE');
        }
    }
}