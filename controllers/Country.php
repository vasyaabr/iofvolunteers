<?php

namespace controllers;


class Country {

    public function getOptionList() {

        echo implode("\n",
            array_column(
                DbProvider::select("SELECT concat('<option value=\"',id,'\">',name,'</option>') AS opt FROM countries ORDER BY name"),
                'opt'
            )
        );

    }

}