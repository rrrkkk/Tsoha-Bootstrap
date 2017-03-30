<?php

class Poll extends BaseModel {
    public $id, $person_id, $name, $startdate, $enddate, $anonymous, $poll_type_id;

    public function __construct($attributes){
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM poll");
        $query->execute();
        $rows = $query->fetchAll();
        $polls = array();

        foreach ($rows as $row) {
            $polls[]
                = new Poll(array(
                    'id' => $row['id'],
                    'person_id' => $row['person_id'],
                    'name' => $row['name'],
                    'startdate' => $row['startdate'],
                    'enddate' => $row['enddate'],
                    'anonymous' => $row['anonymous'],
                    'poll_type_id' => $row['poll_type_id']));
        }
        
        return $polls;
        
    } # all

    public static function find($id) {
        $query = DB::connection()->prepare("SELECT * FROM poll WHERE id = :id LIMIT 1");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch();

        if ($row) {
            $poll
                = new Poll(array(
                    'id' => $row['id'],
                    'person_id' => $row['person_id'],
                    'name' => $row['name'],
                    'startdate' => $row['startdate'],
                    'enddate' => $row['enddate'],
                    'anonymous' => $row['anonymous'],
                    'poll_type_id' => $row['poll_type_is']));
            return $poll;
        }

        return null;

    } # find

    public function save(){
        $query
            = DB::connection()->prepare('INSERT INTO poll (person_id, name, startdate, enddate, anonymous, poll_type_id) VALUES (:person_id, :name, :startdate, :enddate, :anonymous, :poll_type_id) RETURNING id');
        $query->bindValue(':person_id', $this->person_id, PDO::PARAM_INT);
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        $query->bindValue(':startdate', $this->startdate, PDO::PARAM_STR);
        $query->bindValue(':enddate', $this->enddate, PDO::PARAM_STR);
        $query->bindValue(':anonymous', $this->anonymous, PDO::PARAM_BOOL);
        $query->bindValue(':poll_type_id', $this->poll_type_id, PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch();
        $this->id = $row['id'];
    } # save
}

?>