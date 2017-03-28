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

$routes->post('/person', function(){
    PersonController::store();
});

$routes->get('/vote', function() {
    HelloWorldController::vote_list();
});
$routes->get('/vote/1', function() {
    HelloWorldController::vote_show();
});

$routes->get('/vote/edit/1', function() {
    HelloWorldController::vote_edit();
});

$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});
