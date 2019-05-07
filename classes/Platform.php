<?php

namespace iof;

class Platform {

    public function load() : string {

        return TemplateProvider::getInstance()->render('index.twig');

    }

}