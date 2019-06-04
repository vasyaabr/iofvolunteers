<?php

namespace models;


use controllers\DbProvider;

class Volunteer extends Model {

    public static $table = 'volunteers';
    public static $requiredFields = ['name','country', 'email', 'birthdate', 'startO', 'helpDesc'];

    /**
     * Return MySQL condition string for each param name
     * @param string $key parameter name
     * @param mixed $value parameter value
     *
     * @return string
     */
    public static function condition(string $key, $value) : string {

        switch ($key) {
            case 'minage':
                $condition = "YEAR(now()) - YEAR(birthdate) - (DATE_FORMAT(now(), '%m%d') < DATE_FORMAT(birthdate, '%m%d')) >= :{$value}";
                break;
            case 'maxage':
                $condition = "YEAR(now()) - YEAR(birthdate) - (DATE_FORMAT(now(), '%m%d') < DATE_FORMAT(birthdate, '%m%d')) <= :{$value}";
                break;
            case 'oyears':
                $condition = "YEAR(now()) - startO >= :{$value}";
                break;
            case 'maxWorkDuration':
                $condition = "{$value} >= :{$value}";
                break;
            case 'timeToStart':
                $condition = "DATE_FORMAT('{$value}' ,'%Y-%m-01') >= :{$value}";
                break;
            // json arrays
            case 'competitorExp':
            case 'teacherDesc':
            case 'languages':
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
                case 'competitorExp':
                case 'teacherDesc':
                    foreach ($value as $key2 => $value2) {
                        $params["{$key}_{$key2}"] = $value2;
                    }
                    unset($params[$key]);
                    break;
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


    public static function getPreview(string $id) : array {

        if (empty($id)) {
            throw new \Error('Empty preview ID');
        }

        $query = 'SELECT v.*, c.name AS countryName 
            FROM volunteers v
                LEFT JOIN countries c ON v.country=c.id
            WHERE v.id = :id';

        $result = DbProvider::select( $query, ['id' => $id] );

        return count($result) === 1 ? $result[0] : [];

    }

    public static function getLanguages(array $data) : string {

        $result = [];

        if ( !empty($data['languages']) ) {
            foreach ( $data['languages'] as $lang => $desc ) {
                if ( isset( $desc['level'] ) ) {
                    $result[] = "{$lang} ({$desc['level']})";
                }
            }
        }

        return implode(', ', $result);

    }

    public static function getCompetitorExp(array $data) : string {

        if ( !empty($data['competitorExp']) ) {
            $result = [];
            foreach ($data['competitorExp'] as $type => $value) {
                $result[] = "{$value} {$type} events";
            }
            $result = 'compete in ' . implode(', ', $result);
        } else {
            $result = 'Competitor expirience not provided';
        }

        return $result;

    }

    public static function getPreferredContinents(array $data) : string {

        return empty($data['preferredContinents']) ? '' : implode(', ', array_keys($data['preferredContinents']));

    }

    public static function getSkills(array $data) : string {

        $result = [];
        $skillKeys = ['mappingDesc', 'coachDesc','itDesc','eventDesc','teacherDesc'];
        foreach ($skillKeys as $skill) {
            if ( ! empty( $data[$skill] ) ) {
                $info = '';
                foreach ( $data[$skill] as $key => $value ) {
                    if ( $key === 'info' ) {
                        $info = "{$key}: {$value}";
                    } else {
                        $result[$skill][] = ucfirst($key);
                    }
                }
                $result[$skill] = '<b>' . ucfirst(str_replace('Desc','',$skill)).' </b>: '
                                  . implode( ', ', $result[$skill] )
                                  . $info;
            }
        }

        if (!empty($data['otherSkills'])) {
            $result['otherSkills'] = '<b>Other: ' . $data['otherSkills'] . ' </b>';
        }

        $result = implode('<br>',$result);

        return $result;

    }

    public static function getAge(array $data) : int {
        if ( ! empty( $data['birthdate'] ) ) {
            $d1 = \DateTime::createFromFormat('Y-m-d', $data['birthdate']);
            if ($d1) {
                $d2   = new \DateTime();
                $diff = $d2->diff( $d1 );
                return $diff->y;
            }
        }
        return 0;
    }

}