<?php

class PersonController extends BaseController {

    public static function index() {
        $persons = Person::all();
        View::make('person/index.html', array('persons' => $games));
    }
}

?>