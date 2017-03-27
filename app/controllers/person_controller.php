<?php

class PersonController extends BaseController {

    public static function index() {
        $persons = Person::all();
        View::make('person/index.html', array('persons' => $persons));
    }

    public static function show($id) {
        $person = Person::find($id);
        View::make('person/show.html', array('person' => $person));
    }
}

?>