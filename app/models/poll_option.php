<?php

class PollOption extends BaseModel {
    public $id, $poll_id, $name, $description;

    public function __construct($attributes){
        parent::__construct($attributes);
        $this->validators = array('validate_name');
    }

    // it only makes sense to fetch all poll options related to a specific poll.
    public static function all($poll_id) {
        $query = DB::connection()->prepare("SELECT * FROM poll_option WHERE poll_id = :poll_id");
        $query->bindValue(':poll_id', $poll_id, PDO::PARAM_INT);
        $query->execute();
        $rows = $query->fetchAll();
        $poll_options = array();

        foreach ($rows as $row) {
            $poll_options[]
                = new PollOption(array(
                    'id' => $row['id'],
                    'poll_id' => $row['poll_id'],
                    'name' => $row['name'],
                    'description' => $row['description']));
        }
        
        return $poll_options;
        
    } # all

    public static function find($id) {
        $query = DB::connection()->prepare("SELECT * FROM poll_option WHERE id = :id LIMIT 1");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch();

        if ($row) {
            $poll_option
                = new PollOption(array(
                    'id' => $row['id'],
                    'poll_id' => $row['poll_id'],
                    'name' => $row['name'],
                    'description' => $row['description']));
            return $poll_option;
        }

        return null;

    } # find

    public function save() {
        $sql = 'INSERT INTO poll_option (poll_id, name, description)
                VALUES (:poll_id, :name, :description)
                RETURNING id';
        $query = DB::connection()->prepare($sql);
        $query->bindValue(':poll_id', $this->poll_id, PDO::PARAM_INT);
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->execute();
        $row = $query->fetch();
        $this->id = $row['id'];
    } # save

    public function update() {
        $sql = 'UPDATE poll_option
                SET poll_id = :poll_id,
                    name = :name,
                    description = :description
                WHERE id = :id';
        $query = DB::connection()->prepare($sql);
        $query->bindValue(':poll_id', $this->poll_id, PDO::PARAM_INT);
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
    } # save

    # destroy poll option and all related data. order is important.
    public function destroy() {
        $poll_option_id = $this->id;
        $sql1 = "DELETE FROM vote WHERE poll_option_id = :id";
        $query1 = DB::connection()->prepare($sql1);
        $query1->bindValue(':id', $poll_option_id, PDO::PARAM_INT);
        $query1->execute();
        $sql2 = "DELETE FROM poll_option WHERE id = :id";
        $query2 = DB::connection()->prepare($sql2);
        $query2->bindValue(':id', $poll_option_id, PDO::PARAM_INT);
        $query2->execute();
    }

    public function validate_name() {
        return self::validate_strlen($this->name, 1, true, "Liian lyhyt nimi");
    }

}

?>