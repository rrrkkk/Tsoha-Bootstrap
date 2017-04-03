<?php

class Person extends BaseModel {
    # password == hashed password, password_plain == plaintext
    public $id, $name, $username, $email, $password, $password_plain, $admin;

    public function __construct($attributes){
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_username', 'validate_password_plain');
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
                    'password_plain' => '',
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
                    'password_plain' => '',
                    'admin' => $row['admin']));
            return $person;
        }

        return null;

    } # find

    public function save() {
        $this->password = hash("sha256", $this->password_plain); # XXX this is a bootleg - no salt
        $sql = 'INSERT INTO person (name, username, email, password, admin)
                VALUES (:name, :username, :email, :password, :admin)
                RETURNING id';
        $query = DB::connection()->prepare($sql);
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':username', $this->username, PDO::PARAM_STR);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':password', $this->password, PDO::PARAM_STR);
        $query->bindValue(':admin', $this->admin, PDO::PARAM_BOOL);
        $query->execute();
        $row = $query->fetch();
        $this->id = $row['id'];
    } # save

    public function validate_name() {
        return BaseModel::validate_strlen($this->name, 5);
    }

    public function validate_username() {
        return BaseModel::validate_strlen($this->username, 2);
    }

    public function validate_password_plain() {
        return BaseModel::validate_strlen($this->password_plain, 6, false);
    }

}

?>