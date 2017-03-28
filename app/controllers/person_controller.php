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

    public static function create() {
        View::make('person/new.html');
    }

    public static function store(){
        $params = $_POST;
        $person = new Person(array(
            'name' => $params['name'],
            'username' => $params['username'],
            'email' => $params['email'],
            'password' => hash("sha256", $params['password_plain']), # XXX this is a bootleg
            'admin' => $params['admin']
        ));
        
        $person->save();
        
        Redirect::to('/person/' . $person->id, array('message' => 'Henkilö on lisätty.'));
    
    } # store
    
} # PersonController

?>