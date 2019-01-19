<?php

use App\Src\Router;

Router::get('/', 'IndexController::show');

Router::get('/get_petitions', 'IndexController::get');

Router::post('/create_petition', 'IndexController::add');

Router::get('/petition/{int}', 'PetitionController::show');

Router::get('/get_petition/{int}', 'PetitionController::getPetition');

Router::get('/get_new_votes/{int}', 'PetitionController::getNewVotes');

Router::get('/get_votes/{int}', 'PetitionController::getVotes');

Router::post('/vote/{int}', 'PetitionController::vote');