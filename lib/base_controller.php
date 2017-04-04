<?php

class BaseController{

    public static function get_user_logged_in() {
        if(isset($_SESSION['person'])) {
            $person_id = $_SESSION['person'];
            $person = Person::find($person_id);

            return $person;
        }
        
        return null;
    }

    public static function check_logged_in(){
        // Toteuta kirjautumisen tarkistus tähän.
        // Jos käyttäjä ei ole kirjautunut sisään, ohjaa hänet toiselle sivulle (esim. kirjautumissivulle).
    }

}
