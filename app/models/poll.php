<?php

class Poll extends BaseModel {
    public $id, $person_id, $name, $startdate, $enddate, $anonymous, $poll_type_id;
    public $person_name, $poll_type_name; # these are derived from elsewhere in the db.
    public $can_vote, $can_edit; # these are based on date and users' permissions
    public $validators;

    public function __construct($attributes){
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_startdate', 'validate_enddate');
        # XXX can vote: depends on enddate, user logged in & anonymous
        # XXX can edit: only owner can edit
    }

    public static function all() {
        # if not logged in, only show those votes which are anonymous
        if (Person::user_is_logged_in()) {
            $where = '';
        } else {
            $where = 'WHERE anonymous';
        }
        $sql = "SELECT poll.id AS id, person_id, poll.name AS name,
                       startdate, enddate, anonymous, poll_type_id,
                       person.name AS person_name, poll_type.name AS poll_type_name
                FROM poll
                INNER JOIN person ON person.id = person_id
                INNER JOIN poll_type ON poll_type.id = poll_type_id
                $where";
        $query = DB::connection()->prepare($sql);
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
                    'poll_type_id' => $row['poll_type_id'],
                    'person_name' => $row['person_name'],
                    'poll_type_name' => $row['poll_type_name']
                ));
        }
        
        return $polls;
        
    } # all

    public static function find($id) {
        $sql = "SELECT poll.id AS id, person_id, poll.name AS name,
                       startdate, enddate, anonymous, poll_type_id,
                       person.name AS person_name, poll_type.name AS poll_type_name
                FROM poll
                INNER JOIN person ON person.id = person_id
                INNER JOIN poll_type ON poll_type.id = poll_type_id
                WHERE poll.id = :id LIMIT 1";
        $query = DB::connection()->prepare($sql);
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
                    'poll_type_id' => $row['poll_type_id'],
                    'person_name' => $row['person_name'],
                    'poll_type_name' => $row['poll_type_name']));
            return $poll;
        }

        return null;

    } # find

    public function save() {
        $sql = 'INSERT INTO poll (person_id, name, startdate, enddate, anonymous, poll_type_id)
                VALUES (:person_id, :name, :startdate, :enddate, :anonymous, :poll_type_id)
                RETURNING id';
        $query = DB::connection()->prepare($sql);
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

    public function validate_name() {
        return BaseModel::validate_strlen($this->name, 5);
    }

    public function validate_startdate() {
        return BaseModel::validate_date($this->startdate);
    }

    public function validate_enddate() {
        return BaseModel::validate_date($this->enddate);
    }

}

?>