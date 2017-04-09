<?php

class Vote extends BaseModel {
    public $id, $poll_id, $poll_option_id, $time;

    public function __construct($attributes){
        parent::__construct($attributes);
    }

    public function save() {
        $sql1 = 'INSERT INTO vote (poll_id, poll_option_id, time)
                 VALUES (:poll_id, :poll_option_id, NOW()) RETURNING id, time';
        $query1 = DB::connection()->prepare($sql1);
        $query1->bindValue(':poll_id', $this->poll_id, PDO::PARAM_INT);
        $query1->bindValue(':poll_option_id', $this->poll_option_id, PDO::PARAM_INT);
        $query1->execute();
        $row = $query1->fetch();
        $this->id = $row['id'];
        $this->time = $row['time'];
        # mark vote into session ...
        $_SESSION['voted' . $this->poll_id] = true;
        # ... and to voters if identfied
        $poll = Poll::find($this->poll_id);
        if (! $poll->anonymous) {
            $current_user = Person::current_user();
            if ($current_user) {
                $sql2 = 'INSERT INTO voters (poll_id, person_id, time)
                         VALUES (:poll_id, :person_id, NOW())';
                $query2 = DB::connection()->prepare($sql2);
                $query2->bindValue(':poll_id', $this->poll_id, PDO::PARAM_INT);
                $query2->bindValue(':person_id', $current_user->id, PDO::PARAM_INT);
                $query2->execute();
            }
        }
    } # save

    # is current user already voted? also takes session into account
    public static function user_is_voted($poll_id) {
        # first, session
        $session_key = 'voted' . $poll_id;
        if (isset($_SESSION[$session_key])) {
            return true;
        }
        
        # second, voters (if there is current user, ask anyway, regardless of type of poll)
        $current_user = Person::current_user();
        if ($current_user) {
            $sql = "SELECT COUNT(*) AS votes
                    FROM voters
                    WHERE poll_id = :poll_id AND person_id = :person_id";
            $query = DB::connection()->prepare($sql);
            $query->bindValue(':poll_id', $poll_id, PDO::PARAM_INT);
            $query->bindValue(':person_id', $current_user->id, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch();
            if ($row) {
                if ($row['votes'] > 0) {
                    return true;
                }
            }
        }

        # no indication he has voted, so let him go
        return false;
        
    }
    
}

?>