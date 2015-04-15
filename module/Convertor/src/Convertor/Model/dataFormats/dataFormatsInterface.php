<?php

namespace Convertor\Model\dataFormats;

interface dataFormatsInterface
{
    /**
     * Method reads data into internal storage.
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
    public static function getData(&$arr);

    /**
     * Method returns internal data in CSV format.
     *
     * @return string CSV data
     */
    public static function getCT();
}
