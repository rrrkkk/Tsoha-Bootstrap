<?php

class Vote extends BaseModel {
    public $id, $poll_id, $poll_option_id, $time;

    public function __construct($attributes){
        parent::__construct($attributes);
    }

    public function save() {
        $sql = 'INSERT INTO vote (poll_id, poll_option_id, time)
                VALUES (:poll_id, :poll_option_id, NOW()) RETURNING id, time';
        $query = DB::connection()->prepare($sql);
        $query->bindValue(':poll_id', $this->poll_id, PDO::PARAM_INT);
        $query->bindValue(':poll_option_id', $this->poll_option_id, PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch();
        $this->id = $row['id'];
        $this->time = $row['time'];
    } # save
}

?>