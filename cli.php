<?php

/**
 * Install CLI library via composer
 *
 * composer require adhocore/cli
 */
require_once('./vendor/autoload.php');
require_once('./loginserver/EQEmuLoginserverApiClient.php');

/**
 * Test Loginserver client application
 */
$command = new Ahc\Cli\Application('login', 'test');
$color   = new Ahc\Cli\Output\Color;

$loginserver_client = new EQEmuLoginserverApiClient();

$loginserver_client
    ->setApiBaseUrl('http://localhost:6000')
    ->setApiToken('4f182eff-619e-40b6-af52-b480deee69be');

$command
    ->command('user:create', 'Creates loginserver user')
    ->arguments('<username>')
    ->arguments('<password>')
    ->action(
        function ($username, $password) use ($loginserver_client, $color) {

            echo $color->ok('Entered username [' . $username . "]\n");
            echo $color->ok('Entered password [' . $password . "]\n");
            echo $color->ok("\n[Result]\n\n");

            $response = $loginserver_client->createLoginserverAccount($username, $password);

            print_r($response);

            if ($response['data'] && $response['data']['account_id']) {
                echo $color->ok('Account created with ID [' . $response['data']['account_id'] . "]\n");
            }
        }
    );

$command
    ->command('loginserver:list-worlds', 'Lists Loginserver connected worlds')
    ->action(
        function () use ($loginserver_client, $color) {
            $response = $loginserver_client->getServersList();

            print_r($response);
        }
    );

$command
    ->command('user:check-credentials-local', 'Checks if credentials are valid')
    ->arguments('<username>')
    ->arguments('<password>')
    ->action(
        function ($username, $password) use ($loginserver_client, $color) {

            echo $color->ok('Entered username [' . $username . "]\n");
            echo $color->ok('Entered password [' . $password . "]\n");
            echo $color->ok("\n[Result]\n\n");

            $response = $loginserver_client->checkLocalAccountCredentialsValid($username, $password);

            print_r($response);
        }
    );

$command
    ->command('user:check-credentials-external', 'Checks if credentials are valid')
    ->arguments('<username>')
    ->arguments('<password>')
    ->action(
        function ($username, $password) use ($loginserver_client, $color) {

            echo $color->ok('Entered username [' . $username . "]\n");
            echo $color->ok('Entered password [' . $password . "]\n");
            echo $color->ok("\n[Result]\n\n");

            $response = $loginserver_client->checkExternalAccountCredentialsValid($username, $password);

            print_r($response);
        }
    );

$command
    ->command('user:update-local-credentials', 'Update local account credentials')
    ->arguments('<username>')
    ->arguments('<password>')
    ->action(
        function ($username, $password) use ($loginserver_client, $color) {

            echo $color->ok('Entered username [' . $username . "]\n");
            echo $color->ok('Entered password [' . $password . "]\n");
            echo $color->ok("\n[Result]\n\n");

            $response = $loginserver_client->updateLocalAccountCredentials($username, $password);

            print_r($response);
        }
    );

$command->handle($_SERVER['argv']);
