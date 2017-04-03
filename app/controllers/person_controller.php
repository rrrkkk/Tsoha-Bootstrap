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

    public static function store() {
        $params = $_POST;
        $attributes = array(
            'name' => $params['name'],
            'username' => $params['username'],
            'email' => $params['email'],
            'password' => '', # gets hashed in the model
            'password_plain' => $params['password_plain'],
            'admin' => $params['admin']
        );
        
        $person = new Person($attributes);
        $errors = $person->errors();

        if (count($errors) == 0) {
            $person->save();
            Redirect::to('/person/' . $person->id, array('message' => 'Henkilö on lisätty.'));
        } else {
            View::make('person/new.html',
                       array('errors' => $errors, 'attributes' => $attributes));
        }
    
    } # store
    
} # PersonController

?>