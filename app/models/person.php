<?php

class Person extends BaseModel {
    public $id, $name, $username, $email, $password, $admin;

    public function __construct($attributes){
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM person");
        $query->execute();
        $rows = $query->fetchAll();
        $persons = array();

        foreach ($rows as $row) {
            $persons[]
                = new Person(array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'password' => $row['password'],
                    'admin' => $row['admin']));
        }
        
        return $persons;
        
    } # all

    public static function find($id) {
        $query = DB::connection()->prepare("SELECT * FROM person WHERE id = :id LIMIT 1");
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $person
                = new Person(array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'password' => $row['password'],
                    'admin' => $row['admin']));
            return $person;
        }

        return null;

    } # find
}

?>