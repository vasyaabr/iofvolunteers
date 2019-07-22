<?php

namespace models;


class Project extends Model {

    public static $table = 'projects';

    /**
     * Return MySQL condition string for each param name
     * @param string $key parameter name
     * @param mixed $value parameter value
     *
     * @return string
     */
    public static function condition(string $key, $value) : string {

        switch ($key) {
            // json arrays
            case 'offer':
            case 'mappingDesc':
            case 'coachDesc':
            case 'itDesc':
            case 'eventDesc':
            case 'teacherDesc':
                $condition = [];
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            $condition[] = "{$value}->>\"$.{$key2}.{$key3}\" = :{$value}_{$key2}_{$key3}";
                        }
                    } else {
                        if (strpos($key2,'Other') !== false) {
                            $condition[] = "{$value}->>\"$.{$key2}\" LIKE :{$value}_{$key2}";
                        } else {
                            $condition[] = "{$value}->>\"$.{$key2}\" = :{$value}_{$key2}";
                        }
                    }
                }
                $condition = implode(' AND ', $condition);
                break;
            default:
                $condition = parent::condition($key, $value);
                break;
        }

        return $condition;

    }

    /**
     * Params transformation before query
     * @param array $params
     *
     * @return array
     */
    public static function prepareParamsForQuery(array $params) : array {

        foreach ($params as $key => &$value) {
            switch ($key) {
                // 1-dimension json arrays
                case 'offer':
                case 'mappingDesc':
                case 'coachDesc':
                case 'itDesc':
                case 'eventDesc':
                case 'teacherDesc':
                    foreach ($value as $key2 => $value2) {
                        $params["{$key}_{$key2}"] = $value2;
                    }
                    unset($params[$key]);
                    break;
            }
        }

        return $params;

    }

}