<?php

class PollController extends BaseController {

    public static function index() {
        $polls = Poll::all();
        $persons = Person::all();
        $poll_types = PollType::all();
        View::make('poll/index.html',
                   array('polls' => $polls, 'persons' => $persons, 'poll_types' => $poll_types));
    }

    public static function show($id) {
        $poll = Poll::find($id);
        $person = Person::find($poll->person_id);
        $poll_type = PollType::find($poll->poll_type_id);
        View::make('poll/show.html',
                   array('poll' => $poll, 'person' => $person, 'poll_type' => $poll_type));
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