<?php

class PollController extends BaseController {

    # XXX enforcing access mostly missing
    
    public static function index() {
        $polls = Poll::all();
        View::make('poll/index.html',
                   array('polls' => $polls));
    }

    public static function show($id) {
        $poll = Poll::find($id);
        self::check_flag_true($poll->can_view);
        View::make('poll/show.html',
                   array('poll' => $poll));
    }

    public static function edit($id) {
        $poll = Poll::find($id);
        self::check_flag_true($poll->can_edit);
        $persons = Person::all();
        $poll_types = PollType::all();
        View::make('poll/edit.html',
                   array('attributes' => $poll,
                         'persons' => $persons, 'poll_types' => $poll_types));
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
        self::check_logged_in();
        $persons = Person::all();
        $poll_types = PollType::all();
        View::make('poll/new.html',
                   array('persons' => $persons, 'poll_types' => $poll_types));
    }

    public static function store() {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'person_id' => $params['person_id'],
            'name' => $params['name'],
            'startdate' => $params['startdate'],
            'enddate' => $params['enddate'],
            'anonymous' => $params['anonymous'],
            'poll_type_id' => $params['poll_type_id']
        );
        
        $poll = new Poll($attributes);
        $errors = $poll->errors();

        if (count($errors) == 0) {
            $poll->save();
            Redirect::to('/poll/' . $poll->id, array('message' => 'Äänestys on lisätty.'));
        } else {
            $persons = Person::all();
            $poll_types = PollType::all();
            View::make('poll/new.html',
                       array('persons' => $persons, 'poll_types' => $poll_types,
                             'errors' => $errors, 'attributes' => $attributes));
        }
    
    } # store
    
    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'person_id' => $params['person_id'],
            'name' => $params['name'],
            'startdate' => $params['startdate'],
            'enddate' => $params['enddate'],
            'anonymous' => $params['anonymous'],
            'poll_type_id' => $params['poll_type_id']
        );
        
        $poll = new Poll($attributes);
        $errors = $poll->errors();

        if (count($errors) == 0) {
            $poll->update();
            Redirect::to('/poll/' . $poll->id,
                         array('message' => 'Äänestyksen tietoja on päivitetty.'));
        } else {
            $persons = Person::all();
            $poll_types = PollType::all();
            View::make('poll/edit.html',
                       array('persons' => $persons, 'poll_types' => $poll_types,
                             'errors' => $errors, 'attributes' => $attributes));
        }
    
    } # update
    
    public static function destroy($id) {
        self::check_logged_in();
        $poll = new Poll(array('id' => $id));
        $poll->destroy();
        Redirect::to('/poll',
                     array('message' =>
                           'Äänestys ja kaikki siihen liittyvät tiedot on poistettu onnistuneesti!'));
    }

} # PollController

?>