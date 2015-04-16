<?php

namespace Convertor\Model\dataFormats;

interface dataFormatsInterface
{
    /**
     * Method reads data into array - internal dataHoder format.
     *
     * @param array  &$arr Array to store data
     * @param string $str  Input data string
     */
    public static function readData(&$arr, $str);

    /**
     * Methods gets Content-Type header value for input file.
     *
     * @return string
     */
    public static function getCT();

    /**
     * Method returns supplied data (given in dataHolder internal format)
     * in specific format.
     *
     * @return string Formatted data
     */
    public static function getData(&$arr);
}
