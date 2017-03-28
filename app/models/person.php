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
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
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

    public function save(){
        $query
            = DB::connection()->prepare('INSERT
                                         INTO person (name, username, email, password, admin)
                                         VALUES (:name, :username, :email, :password, :admin)');
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':username', $this->username, PDO::PARAM_STR);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':password', $this->password, PDO::PARAM_STR);
        $query->bindValue(':admin', $this->admin, PDO::PARAM_BOOL);
        $query->execute();
        $this->id = PDO::lastInsertId();
    } # save
}

?>