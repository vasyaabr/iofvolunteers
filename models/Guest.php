<?php

namespace models;


class Guest extends Model {

    public static $table = 'guests';

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

}