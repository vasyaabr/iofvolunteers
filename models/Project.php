<?php

namespace models;


class Project extends Model {

    public static $table = 'projects';
    public const CONTACT_TYPE='volunteer=>project';
    public static $requiredFields = ['name','country', 'email'];

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
                            $condition[] = "{$key}->>\"$.{$key2}.{$key3}\" = :{$key}_{$key2}_{$key3}";
                        }
                    } else {
                        if (strpos($key2,'Other') !== false) {
                            $condition[] = "{$key}->>\"$.{$key2}\" LIKE :{$key}_{$key2}";
                        } else {
                            $condition[] = "{$key}->>\"$.{$key2}\" = :{$key}_{$key2}";
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

    public static function getOffer(array $data) : string {

        return empty($data['offer']) ? '' : implode(', ', array_keys($data['offer']));

    }

    public static function getExpirience(array $data) : string {

        $result = [];

        if (!empty($data['oworkLocalExp'])) {
            foreach ($data['oworkLocalExp'] as $key => $exp) {
                if ($key === 'experience') {
                    $result[] = 'Local events: '.$exp;
                } elseif ($exp == 1) {
                    $result[] = 'Local role: '.$key;
                } else {
                    $result[] = 'Local ' . $key . ': '.$exp;
                }
            }
        }

        if (!empty($data['oworkInternationalExp'])) {
            foreach ($data['oworkInternationalExp'] as $key => $exp) {
                if ($key === 'experience') {
                    $result[] = 'International events: '.$exp;
                } elseif ($exp == 1) {
                    $result[] = 'International role: '.$key;
                } else {
                    $result[] = 'International ' . $key . ': '.$exp;
                }
            }
        }

        return implode('<br/>',$result);

    }

}