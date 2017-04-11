<?php

class Person extends BaseModel {
    # password == hashed password, password_plain == plaintext
    public $id, $name, $username, $email, $password, $password_plain, $admin;

    public function __construct($attributes){
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_username', 'validate_password');
    }

    public static function all() {
        # return nothing if user not admin
        if (! Person::user_is_admin()) {
            return null;
        }
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

    public static function find_username($username) {
        $query = DB::connection()->prepare("SELECT * FROM person WHERE username = :username LIMIT 1");
        $query->bindValue(':username', $username, PDO::PARAM_STR);
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

    } # find_username

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

    public function update() {
        $sql1 = 'UPDATE person
                 SET name = :name,
                     username = :username,
                     email = :email,
                     admin = :admin
                 WHERE id = :id';
        $query1 = DB::connection()->prepare($sql1);
        $query1->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query1->bindValue(':username', $this->username, PDO::PARAM_STR);
        $query1->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query1->bindValue(':admin', $this->admin, PDO::PARAM_BOOL);
        $query1->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query1->execute();
        if (strlen ($this->password_plain) > 0) {
            $this->password = hash("sha256", $this->password_plain); # XXX this is a bootleg - no salt
            $sql2 = 'UPDATE person SET password = :password WHERE id = :id';
            $query2 = DB::connection()->prepare($sql2);
            $query2->bindValue(':password', $this->password, PDO::PARAM_STR);
            $query2->bindValue(':id', $this->id, PDO::PARAM_INT);
            $query2->execute();
        }
    } # update

    # destroy person and all related data. order is important.
    public function destroy() {
        $person_id = $this->id;
        $sql2 = "DELETE FROM vote WHERE poll_id IN (SELECT id FROM poll WHERE person_id = :id)";
        $query2 = DB::connection()->prepare($sql2);
        $query2->bindValue(':id', $person_id, PDO::PARAM_INT);
        $query2->execute();
        $sql1 = "DELETE FROM poll_option WHERE poll_id IN (SELECT id FROM poll WHERE person_id = :id)";
        $query1 = DB::connection()->prepare($sql1);
        $query1->bindValue(':id', $person_id, PDO::PARAM_INT);
        $query1->execute();
        $sql3 = "DELETE FROM poll WHERE person_id = :id";
        $query3 = DB::connection()->prepare($sql3);
        $query3->bindValue(':id', $person_id, PDO::PARAM_INT);
        $query3->execute();
        $sql4 = "DELETE FROM person WHERE id = :id";
        $query4 = DB::connection()->prepare($sql4);
        $query4->bindValue(':id', $person_id, PDO::PARAM_INT);
        $query4->execute();
    }

    public static function authenticate($username, $password_plain) {
        $person = Person::find_username($username);

        if ($person == null) {
            # käyttäjätunnusta ei löydy, ei jatkoon
            return null;
        }

        $password_hashed = hash("sha256", $password_plain);
        if ($password_hashed != $person->password) {
            # väärä salasana, ei jatkoon
            return null;
        }

        # kelpaa.
        return $person;
        
    } # authenticate

    public static function current_user() {
        if(isset($_SESSION['person'])) {
            $person_id = $_SESSION['person'];
            $person = Person::find($person_id);
            return $person;
        }
        return null;
    }

    public static function user_is_admin() {
        $person = Person::current_user();
        if ($person == null) {
            return false;
        }
        if ($person->admin) {
            return true;
        }
        return false;
    }

    public static function user_is_logged_in() {
        $person = Person::current_user();
        if ($person == null) {
            return false;
        }
        return true;
    }

    public function validate_name() {
        return self::validate_strlen($this->name, 5, true, "Liian lyhyt nimi");
    }

    public function validate_username() {
        return self::validate_strlen($this->username, 2, true, "Liian lyhyt käyttäjätunnus");
    }

    # basic idea is to validate new pw only if we are actually setting it
    # if:
    #  password_plain longer than 1, do check.
    #  if empty:
    #   check only if password also empty.
    public function validate_password() {
        $len_plain = strlen($this->password_plain);
        if ($len_plain > 1) {
            return self::validate_strlen($this->password_plain, 6, false, "Liian lyhyt salasana");
        }
        $len = strlen($this->password);
        if ($len == 0) {
            return self::validate_strlen($this->password_plain, 6, false, "Liian lyhyt salasana");
        }
        return array();
    }

}

?>