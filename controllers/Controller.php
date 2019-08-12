<?php

namespace controllers;

use models\User;

abstract class Controller
{

    /**
     * Helper function to group all methods
     * @param array $values
     * @return array
     */
    public static function prepareData(array $values) : array {

        return self::flatten(self::decode(array_filter($values)));

    }

    /**
     * Function decode all JSON values in array
     * @param array $values
     * @return array
     */
    public static function decode(array $values) : array {

        foreach ($values as &$value) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        return $values;

    }

    /**
     * Flatten array, prepare it for filling HTML form with jquery
     * @param array $values
     * @return array
     */
    public static function flatten(array $values) : array {

        // TODO: rewrite to recursive function
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            $values["{$key}[{$key2}][{$key3}]"] = $value3;
                        }
                    } else {
                        $values["{$key}[{$key2}]"] = $value2;
                    }
                }
                unset($values[$key]);
            }
        }

        return $values;

    }

    /**
     * Custom json encoder
     * @param array $value
     *
     * @return string
     */
    public static function json_enc(array $value) : string {

        $encoded = json_encode($value, JSON_UNESCAPED_UNICODE);

        return json_last_error() === JSON_ERROR_NONE
            ? str_replace('\"','\\"',$encoded)
            : '';

    }

}