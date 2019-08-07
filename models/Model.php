<?php

namespace models;


use controllers\DbProvider;

abstract class Model {

    public static $table = '';
    public static $key = 'id';

    /**
     * Add new row to table
     * @param array $params
     *
     * @return bool
     */
    public static function add(array $params) : bool {

        $valueMasks = implode(',',
            array_map(function($v) { return ':'.$v; },array_keys($params))
        );
        $keys = implode(',',array_keys($params));

        $query = 'INSERT INTO ' . static::$table . " ({$keys}) VALUES ({$valueMasks})";
        $statement = DbProvider::getInstance()->prepare( $query );
        return $statement->execute( $params );

    }

    /**
     * Update existing row
     * @param array $params
     *
     * @return bool
     * @throws \Error
     */
    public static function update(array $params) : bool {

        if (!isset($params[static::$key])) {
            throw new \Error('Update key not set');
        }

        $valueMasks = implode(',',
            array_map(function($v) { return "{$v} = :{$v}"; },array_keys($params))
        );

        $query = 'UPDATE ' . static::$table . " SET {$valueMasks} WHERE " . static::$key . ' = :' . static::$key;
        $statement = DbProvider::getInstance()->prepare( $query );
        return $statement->execute( $params );

    }

    /**
     * Return single row or empty array
     * @param array $params
     *
     * @return array
     * @throws \Error
     */
    public static function getSingle(array $params) : array {

        $query = self::query($params);
        $params = static::prepareParamsForQuery($params);
        $result = DbProvider::select($query, $params);
        return count($result) === 1 ? $result[0] : [];

    }

    /**
     * Return array of rows
     * @param array $params
     *
     * @return array
     * @throws \Error
     */
    public static function get(array $params, array $fields = []) : array {

        $query = self::query($params, $fields);
        $params = static::prepareParamsForQuery($params);
        return DbProvider::select($query, $params);

    }

    /**
     * Stub for params transformation in child classes
     * @param array $params
     *
     * @return array
     */
    public static function prepareParamsForQuery(array $params) : array {

        return self::array_flat($params);

    }

    public static function array_flat(array $array) : array
    {
        $result = array();

        foreach ($array as $key => $value)
        {
            if (is_array($value))
            {
                $result = array_merge($result, self::array_flat($value, $key));
            }
            else
            {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Return query to table
     * @param array $params
     *
     * @return string
     * @throws \Error
     */
    public static function query(array $params, array $fields = []) : string {

        $where = [];

        foreach ($params as $key => $value) {
            $where[] = static::condition($key, $value);
        }

        $queryFields = empty($fields) ? '*' : implode(',', $fields);
        $whereString = empty($where) ? '' : (' WHERE ' . implode(' AND ', $where));

        return "SELECT {$queryFields} FROM " . static::$table . $whereString . ' ORDER BY ' . static::$key;

    }

    /**
     * Return MySQL condition string for each param name
     * @param string $key parameter name
     * @param mixed $value parameter value
     *
     * @return string
     */
    public static function condition(string $key, $value) : string {

        if (is_array($value)) {
            $condition2 = [];
            foreach ($value as $key2 => $value2) {
                $condition2[] = "{$key2} = :{$key2}";
            }
            $condition = ' ( ' . implode(' OR ',$condition2) . ' ) ';
        } else {
            $condition = "{$key} = :{$key}";
        }

        return $condition;

    }

    public static function getLanguages(array $data) : string {

        $result = [];

        if ( !empty($data['languages']) ) {
            foreach ( $data['languages'] as $lang => $desc ) {
                if ( isset( $desc['level'] ) ) {
                    $result[] = "{$lang} ({$desc['level']})";
                }
            }
            if (isset($data['languages']['Other'])) {
                $result[] = $data['languages']['Other'];
            }
        }

        return implode(', ', $result);

    }

}