<?php

declare(strict_types=1);

namespace MagmaDataLayer;

trait DataLayerTrait
{

    /**
     * Returns a flatten array from a multidimensional array
     *
     * @param array $array the multidimensional array
     * @return array
     * @throws InvalidArgumentException
     */
    public function flattenArray(array $array = null)
    {
        //Validator::isArray($array);

        if (is_array($array)) {
            $arraySingle = [];
            foreach ($array as $arr) {
                foreach ($arr as $val) {
                    $arraySingle[] = $val;
                }
            }
            return $arraySingle;
        }
    }

    /**
     * Returns a flatten array from a multidimensional array recursively
     *
     * @param array $array the multidimensional array
     * @return array
     */
    public function flattenArrayRecursive(array $array = null)
    {
        //Validator::isArray($array);

        $flatArray = array();
        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array)) as $value) {
            $flatArray[] = $value;
        }
        return $flatArray;
    }

}