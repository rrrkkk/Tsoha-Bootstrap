<?php

class BaseController{

    public static function get_user_logged_in() {
        return Person::current_user();
    }

    # if current user is not admin, barf
    public static function check_admin () {
        if (! Person::user_is_admin()) {
            View::make('main/error.html');
        }
    }

    # check a flag, if not true, barf
    public static function check_flag_true ($flag) {
        if (! $flag) {
            View::make('main/error.html');
        }
    }

    public static function check_logged_in(){
        // Toteuta kirjautumisen tarkistus tähän.
        // Jos käyttäjä ei ole kirjautunut sisään, ohjaa hänet toiselle sivulle (esim. kirjautumissivulle).
        if (! Person::user_is_logged_in()) {
            View::make('main/error.html');
        }
    }

}
