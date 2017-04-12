<?php

class VoteController extends BaseController {

    public static function show($id) {
        $vote = Vote::find($id);
        View::make('vote/show.html',
                   array('vote' => $vote));
    }

    public static function store() {
        $params = $_POST;
        $vote = new Vote(array(
            'poll_id' => $params['poll_id'],
            'poll_option_id' => $params['poll_option_id'],
            'time' => '' # timestamped in the model
        ));
        
        $vote->save();
        
        Redirect::to('/poll/results/' . $vote->poll_id, array('message' => 'Äänesi on rekisteröity.'));
    
    } # store
    
} # VoteController

?>