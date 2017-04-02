<?php

class PollOption extends BaseModel {
    public $id, $poll_id, $name, $description;

    public function __construct($attributes){
        parent::__construct($attributes);
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
}

?>