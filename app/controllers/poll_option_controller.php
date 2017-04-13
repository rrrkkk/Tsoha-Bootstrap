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

    public static function edit($id) {
        $poll_option = PollOption::find($id);
        $poll = Poll::find($poll_option->poll_id);
        self::check_flag_true($poll->can_edit);
        View::make('poll_option/edit.html',
                   array('attributes' => $poll_option, 'poll' => $poll));
    }

    public static function create($poll_id) {
        $poll = Poll::find($poll_id);
        self::check_flag_true($poll->can_edit);
        View::make('poll_option/new.html', array('poll' => $poll));
    }

    public static function store() {
        self::check_logged_in();
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
            Redirect::to('/poll_option/poll/' . $poll_option->poll_id,
                         array('message' => 'Äänestysvaihtoehto on lisätty.'));
        } else {
            $poll = Poll::find($poll_option->poll_id);
            View::make('poll_option/new.html',
                       array('poll' => $poll,
                             'errors' => $errors, 'attributes' => $attributes));
        }
    
    } # store
    
    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'poll_id' => $params['poll_id'],
            'name' => $params['name'],
            'description' => $params['description']
        );

        $poll_option = new PollOption($attributes);
        $errors = $poll_option->errors();

        if (count($errors) == 0) {
            $poll_option->update();
            Redirect::to('/poll_option/poll/' . $poll_option->poll_id,
                         array('message' => 'Äänestysvaihtoehtoa on päivitetty.'));
        } else {
            $poll = Poll::find($poll_option->poll_id);
            View::make('poll_option/edit.html',
                       array('poll' => $poll,
                             'errors' => $errors, 'attributes' => $attributes));
        }
    
    } # update
    
    public static function destroy($id) {
        self::check_logged_in();
        $poll_option = new PollOption::find($id);
        $poll_id = $poll_option->poll_id;
        $poll_option->destroy();
        Redirect::to('/poll_option/poll/' . $poll_id,
                     array('message' => 'Äänestysvaihtoehto ja kaikki siihen liittyvät tiedot poistettu onnistuneesti!'));
    }
    
} # PollOptionController

?>