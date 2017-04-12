<?php

class PollStat extends BaseModel {
    public $date, $votes;

    public function __construct($attributes){
        parent::__construct($attributes);
    }

    public static function all($poll_id) {
        $poll_stats = array();
        $poll_stat = null;

        # outer loop: run all votes for this poll.
        $prev_date = '';
        $sql1 = "SELECT time FROM vote WHERE poll_id = :poll_id ORDER BY time";
        $query1 = DB::connection()->prepare($sql1);
        $query1->bindValue(':poll_id', $poll_id, PDO::PARAM_INT);
        $query1->execute();
        $rows1 = $query1->fetchAll();

        foreach ($rows1 as $row1) {
            $this_date = substr($row1->['time'], 0, 10);
            if ($this_date != $prev_date) {
                if ($poll_stat != null) {
                    $poll_stats[] = $poll_stat;
                }
                $poll_stat = new PollStat(array('date' => $this_date, 'votes' => 0));
                $prev_date = $this_date;
            }
            $poll_stat->votes ++;
        }
        if ($poll_stat != null) {
            $poll_stats[] = $poll_stat;
        }
        
        return $poll_stats;
        
    } # all

}

?>