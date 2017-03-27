<?php

class MainController extends BaseController{

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

    public static function sandbox(){
        // Testaa koodiasi täällä
        $riku = Person::find(1);
        $persons = Person::all();
        Kint::dump($persons);
        Kint::dump($riku);
    }
}
