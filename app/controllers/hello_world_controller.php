<?php

class HelloWorldController extends BaseController{

    /* irrelevant now 
    public static function index(){
        View::make('suunnitelmat/index.html');
    }

    public static function vote_list(){
        View::make('suunnitelmat/vote_list.html');
    }

    public static function vote_show(){
        View::make('suunnitelmat/vote_show.html');
    }
    
    public static function vote_edit(){
        View::make('suunnitelmat/vote_edit.html');
    }
    
    public static function login(){
        View::make('suunnitelmat/login.html');
    }
    */

    public static function sandbox(){
        // Testaa koodiasi täällä
        $poll = new Poll(array(
            'person_id' => 1,
            'name' => 'Nimi',
            'startdate' => 'Tekstiä',
            'enddate' => '1.4.2017'
        ));
        $errors = $poll->errors();
        
        Kint::dump($errors);

        Kint::dump($_SERVER);
        Kint::dump($_REQUEST);
        Kint::dump($GLOBALS);
    }
}
