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

    public static function edit($id) {
        $person = Person::find($id);
        View::make('person/edit.html', array('attributes' => $person));
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
    
    public static function update($id) {
        $params = $_POST;
        $attributes = array(
            'id' => $id,
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
            $person->update();
            Redirect::to('/person/' . $person->id,
                         array('message' => 'Henkilön tietoja on päivitetty.'));
        } else {
            View::make('person/edit.html',
                       array('errors' => $errors, 'attributes' => $attributes));
        }
    
    } # update

    public static function destroy($id) {
        $person = new Person(array('id' => $id));
        $person->destroy();
        Redirect::to('/person', array('message' => 'Henkilö ja kaikki häneen liittyvät tiedot on poistettu onnistuneesti!'));
    }

    public static function login() {
        View::make('person/login.html');
    }

    public static function handle_login() {
        $params = $_POST;

        $person = Person::authenticate($params['username'], $params['password_plain']);

        if(!$person){
            View::make('person/login.html',
                       array('error' => 'Väärä käyttäjätunnus tai salasana!',
                             'username' => $params['username']));
        } else {
            $_SESSION['person'] = $person->id;
            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $person->name . '!'));
        }
    }
    
    public static function logout() {
        View::make('person/logout.html');
    }

    public static function handle_logout() {
        unset $_SESSION['person'];
        Redirect::to('/', array('message' => 'Sinut on kirjattu ulos.'));
    }
    
} # PersonController

?>