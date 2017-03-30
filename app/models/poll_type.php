<?php

class PollType extends BaseModel {
    public $id, $name;

    public function __construct($attributes){
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM poll_type");
        $query->execute();
        $rows = $query->fetchAll();
        $poll_types = array();

        foreach ($rows as $row) {
            $poll_types[]
                = new PollType(array(
                    'id' => $row['id'],
                    'name' => $row['name']));
        }
        
        return $poll_types;
        
    } # all

    public static function find($id) {
        $query = DB::connection()->prepare("SELECT * FROM poll_type WHERE id = :id LIMIT 1");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch();

        if ($row) {
            $poll_type
                = new PollType(array(
                    'id' => $row['id'],
                    'name' => $row['name']));
            return $poll_type;
        }

        return null;

    } # find

    // static table, no save()

}

?>