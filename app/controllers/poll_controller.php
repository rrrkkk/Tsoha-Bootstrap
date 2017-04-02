<?php

class PollController extends BaseController {

    public static function index() {
        $polls = Poll::all();
        View::make('poll/index.html',
                   array('polls' => $polls));
    }

    public static function show($id) {
        $poll = Poll::find($id);
        View::make('poll/show.html',
                   array('poll' => $poll));
    }

    # vote on a poll
    public static function vote($id) {
        $poll = Poll::find($id);
        $poll_options = PollOption::all($id);
        View::make('poll/vote.html',
                   array('poll' => $poll, 'poll_options' => $poll_options));
    }

    # show poll results (according to poll.poll_type_id)
    public static function results($id) {
        $poll = Poll::find($id);
        $poll_results = PollResult::all($id);
        View::make('poll/results.html',
                   array('poll' => $poll, 'poll_results' => $poll_results));
    }

    public static function create() {
        $persons = Person::all();
        $poll_types = PollType::all();
        View::make('poll/new.html',
                   array('persons' => $persons, 'poll_types' => $poll_types));
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