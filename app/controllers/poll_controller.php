<?php

class PollController extends BaseController {

    public static function index() {
        $polls = Poll::all();
        View::make('poll/index.html', array('polls' => $polls));
    }

    public static function show($id) {
        $poll = Poll::find($id);
        View::make('poll/show.html', array('poll' => $poll));
    }

    public static function create() {
        View::make('poll/new.html');
    }

    public static function store(){
        $params = $_POST;
        $poll = new Poll(array(
            'person_id' => $params['person_id'],
            'name' => $params['name'],
            'startdate' => $params['startdate'],
            'enddate' => $params['enddate'],
            'anonymous' => $params['anonymous'],
            'poll_type' => $params['poll_type_id']
        ));
        
        $poll->save();
        
        Redirect::to('/poll/' . $poll->id, array('message' => 'Äänestys on lisätty.'));
    
    } # store
    
} # PollController

?>