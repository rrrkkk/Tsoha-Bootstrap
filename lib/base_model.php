<?php

class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach($attributes as $attribute => $value){
            // Jos avaimen niminen attribuutti on olemassa...
            if(property_exists($this, $attribute)){
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function errors() {
        // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        $errors = array();
        
        foreach($this->validators as $validator){
            // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon
            $validator_errors = $this->{$validator}();
            $errors = array_merge($errors, $validator_errors);
        }
        
        return $errors;
    }

    public function validate_date($date) {
        $errors = array();

        $d = date_parse($date);
        if (! checkdate($d['month'], $d['day'], $d['year'])) {
            $errors[] = "Virheellinen päivämäärä: $date";
        }

        return $errors;
    }

    public function validate_strlen($str, $min_len, $show_value = true, $error_msg) {
        $errors = array();

        $len = strlen($str);
        if ($len < $min_len) {
            $error = $error_msg . " ($len < $min_len)";
            if ($show_value) {
                $error .= ": $str";
            }
            $errors[] = $error;
        }

        return $errors;
    }

}
