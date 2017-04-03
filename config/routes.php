<?php

$routes->get('/', function() {
    MainController::index();
});

$routes->get('/person', function() {
    PersonController::index();
});

$routes->get('/person/new', function(){
    PersonController::create();
});

$routes->get('/person/:id', function($id){
    PersonController::show($id);
});

$routes->get('/person/:id/edit', function($id){
    PersonController::edit($id);
});

$routes->post('/person', function(){
    PersonController::store();
});

$routes->post('/person/:id/edit', function($id){
    PersonController::update($id);
});

$routes->post('/person/:id/destroy', function($id){
    PersonController::destroy($id);
});

$routes->get('/poll', function() {
    PollController::index();
});

$routes->get('/poll/new', function(){
    PollController::create();
});

$routes->get('/poll/:id', function($id){
    PollController::show($id);
});

/* XXX does not work, yet */
$routes->get('/poll/:id/edit', function($id) {
    PollController::edit($id);
});

$routes->get('/poll/vote/:id', function($id) {
    PollController::vote($id);
});

$routes->get('/poll/results/:id', function($id) {
    PollController::results($id);
});

$routes->post('/poll', function(){
    PollController::store();
});

$routes->post('/vote', function(){
    VoteController::store();
});

$routes->get('/poll_option/poll/:poll_id', function($poll_id) {
    PollOptionController::index($poll_id);
});

$routes->get('/poll_option/new/:poll_id', function($poll_id){
    PollOptionController::create($poll_id);
});

$routes->get('/poll_option/:id', function($id){
    PollOptionController::show($id);
});

$routes->get('/poll_option/:id/edit', function($id){
    PollOptionController::edit($id);
});

$routes->post('/poll_option', function(){
    PollOptionController::store();
});

/*
$routes->get('/vote', function() {
    HelloWorldController::vote_list();
});

$routes->get('/vote/1', function() {
    HelloWorldController::vote_show();
});

$routes->get('/vote/edit/1', function() {
    HelloWorldController::vote_edit();
});
*/

$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});
