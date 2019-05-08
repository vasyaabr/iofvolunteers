<?php

namespace iof;


class Country {

    public function getOptionList() {

        echo implode("\n",
            array_column(
                DbProvider::run("SELECT concat('<option value=\"',id,'\">',name,'</option>') AS opt FROM countries ORDER BY name"),
                'opt'
            )
        );

    }

}