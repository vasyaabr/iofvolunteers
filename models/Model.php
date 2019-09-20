<?php

namespace models;


use controllers\DbProvider;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

abstract class Model {

    public static $table = '';
    public static $key = 'id';

    /**
     * Stub for default validators list
     * @return array
     */
    public static function getValidators() : array {
        return [];
    }

    /**
     * Validate array of parameters
     * @param array $params
     * @param bool $strict use strict validation
     * @return array
     */
    public static function validateAll(array $params, bool $strict = false) : array {

        $validators = static::getValidators();
        $errors = [];

        if ($strict) {
            // Strict validation, will check for missing values
            foreach ($validators as $name => $validator) {
                try {
                    $validator->assert($params[$name] ?? null);
                } catch(NestedValidationException $exception) {
                    $errors += $exception->getMessages();
                }
            }
        } else {
            // Non-strict validation, check only presented values
            foreach ($params as $key => $param) {
                if (isset($validators[$key])) {
                    try {
                        $validators[$key]->assert($param);
                    } catch (NestedValidationException $exception) {
                        $errors += $exception->getMessages();
                    }
                }
            }
        }

        return $errors;

    }

    /**
     * Validates list of parameters
     * @param array $list
     * @param array $params
     * @return array
     */
    public static function validateList(array $list, array $params) : array {

        $validators = static::getValidators();
        $errors = [];

        foreach ($list as $name) {
            if (isset($validators[$name])) {
                try {
                    $validators[$name]->assert($params[$name] ?? null);
                } catch (NestedValidationException $exception) {
                    $errors += $exception->getMessages();
                }
            }
        }

        return $errors;

    }

    /**
     * Validate single parameter
     * @param string $key
     * @param string $value
     * @return array
     */
    public static function validate(string $key, string $value) : array {

        $validators = static::getValidators();

        if(isset($validators[$key])) {
            try {
                $validators[$key]->assert($value);
            } catch(NestedValidationException $exception) {
                return $exception->getMessages();
            }
        }

        return [];

    }

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
        $keys = implode('`,`',array_keys($params));

        $query = 'INSERT INTO ' . static::$table . " (`{$keys}`) VALUES ({$valueMasks})";
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
            array_map(function($v) { return "`{$v}` = :{$v}"; },array_keys($params))
        );

        $query = 'UPDATE ' . static::$table . " SET {$valueMasks} WHERE `" . static::$key . '` = :' . static::$key;
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

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::array_flat($value, $key));
            } else {
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

        return "SELECT {$queryFields} FROM " . static::$table . $whereString . ' ORDER BY `' . static::$key . '`';

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
                $condition2[] = "`{$key2}` = :{$key2}";
            }
            $condition = ' ( ' . implode(' OR ',$condition2) . ' ) ';
        } else {
            $condition = "`{$key}` = :{$key}";
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

    public static function getCountry(array $data) : string {

        if (!empty($data['country'])) {
            $country = Country::getSingle([ 'id' => $data['country'] ]);
            return $country['name'];
            }
        return '';

    }

    public static function switchActiveState($id) : bool {

        $query = 'UPDATE '.static::$table.' SET Active = not Active WHERE `'.static::$key.'` = :id';
        $statement = DbProvider::getInstance()->prepare( $query );
        return $statement->execute( ['id' => $id] );

    }

}