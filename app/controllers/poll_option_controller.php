<?php

class PollOptionController extends BaseController {

    public static function index($poll_id) {
        $poll_options = PollOption::all($poll_id);
        $poll = Poll::find($poll_id);
        View::make('poll_option/index.html',
                   array('poll_options' => $poll_options, 'poll' => $poll));
    }

    public static function show($id) {
        $poll_option = PollOption::find($id);
        $poll = Poll::find($poll_option->poll_id);
        View::make('poll_option/show.html',
                   array('poll_option' => $poll_option, 'poll' => $poll));
    }

    public static function create($poll_id) {
        $poll = Poll::find($poll_id);
        View::make('poll_option/new.html', array('poll' => $poll));
    }

    public static function store(){
        $params = $_POST;
        $attributes = array(
            'poll_id' => $params['poll_id'],
            'name' => $params['name'],
            'description' => $params['description']
        );

        $poll_option = new PollOption($attributes);
        $errors = $poll_option->errors();

        if (count($errors) == 0) {
            $poll_option->save();
            Redirect::to('/poll_option/' . $poll_option->id,
                         array('message' => 'Äänestysvaihtoehto on lisätty.'));
        } else {
            $poll = Poll::find($poll_option->poll_id);
            View::make('poll_option/new.html',
                       array('poll' => $poll,
                             'errors' => $errors, 'attributes' => $attributes));
        }
    
    } # store
    
} # PollController

?>