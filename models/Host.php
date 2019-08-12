<?php

namespace models;


class Host extends Model {

    public static $table = 'hosts';

    /**
     * Return MySQL condition string for each param name
     * @param string $key parameter name
     * @param mixed $value parameter value
     *
     * @return string
     */
    public static function condition(string $key, $value) : string {

        switch ($key) {
            case 'maxDuration':
                $condition = "{$key} >= :{$key}";
                break;
            // json arrays
            case 'languages':
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
                // 2-dimension json arrays
                case 'languages':
                    foreach ($value as $key2 => $value2) {
                        if (strpos($key2, 'Other') !== false) {
                            $params["{$key}_{$key2}"] = "'%$value2%'";
                        } else {
                            foreach ($value2 as $key3 => $value3) {
                                $params["{$key}_{$key2}_{$key3}"] = $value3;
                            }
                        }
                    }
                    unset($params[$key]);
                    break;
            }
        }

        return $params;

    }

    public static function getOffer(array $data) : string {

        $result = [];
        $keys = ['food','accomodation','events','o-training','local_tourism','loan_car','loan_bike', 'distance_to_public_transport', 'other'];

        foreach ($keys as $offer) {
            if ( ! empty( $data['offer'][$offer] ) ) {

                $info = str_replace('_',' ',ucfirst($offer));

                if ($offer === 'food' && !empty($data['offer']['food_price'])) {
                    $info .= "({$data['offer']['food_price']})";
                } else if ($offer === 'accomodation' && !empty($data['offer']['accomodation_price'])) {
                    $info .= "({$data['offer']['accomodation_price']})";
                } else if ($data['offer'][$offer] !== '1') {
                    $info .= "({$data['offer'][$offer]})";
                }

                $result[$offer] = $info;
            }
        }

        return implode('<br>',$result);

    }

}