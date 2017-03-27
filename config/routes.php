<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/person', function() {
    PersonController::index();
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
