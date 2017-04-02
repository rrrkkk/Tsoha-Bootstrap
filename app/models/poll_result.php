<?php

class PollResult extends BaseModel {
    public $is_top, $poll_option_id, $poll_option_name, $votes;

    public function __construct($attributes){
        parent::__construct($attributes);
    }

    // it only makes sense to fetch poll results related to a specific poll.
    public static function all($poll_id) {
        $poll_results = array();
        $top_votes = 0;

        # outer loop: run all possible options for this poll.
        $query1 = DB::connection()->prepare("SELECT * FROM poll_option WHERE poll_id = :poll_id");
        $query1->bindValue(':poll_id', $poll_id, PDO::PARAM_INT);
        $query1->execute();
        $rows1 = $query1->fetchAll();

        foreach ($rows1 as $row1) {
            # inner loop: calculate votes to specific poll options
            $poll_option_id = $row1['id'];
            $poll_option_name = $row1['name'];
            $sql2 = "SELECT COUNT(*) AS votes
                     FROM vote
                     WHERE poll_id = :poll_id AND poll_option_id = :poll_option_id";
            $query2 = DB::connection()->prepare($sql2);
            $query2->bindValue(':poll_id', $poll_id, PDO::PARAM_INT);
            $query2->bindValue(':poll_option_id', $poll_option_id, PDO::PARAM_INT);
            $query2->execute();
            $row2 = $query2->fetch();
            $votes = $row2['votes'];
            if ($votes > $top_votes) {
                $top_votes = $votes;
            }
            $poll_results[]
                = new PollResult(array(
                    'is_top' => 0,
                    'poll_option_id' => $poll_option_id,
                    'poll_option_name' => $poll_option_name,
                    'votes' => $votes
                ));
        }
        
        # mark the top contester(s)
        foreach ($poll_results as $poll_result) {
            if ($poll_result->votes == $top_votes) {
                $poll_result->is_top = 1;
            }
        }
        
        return $poll_results;
        
    } # all

}

?>