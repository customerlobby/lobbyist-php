<?php

// Tested on PHP 5.2, 5.3

if (!function_exists('curl_init')) {
  throw new Exception('Lobbyist needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Lobbyist needs the JSON PHP extension.');
}

// Lobbyist singleton
require(dirname(__FILE__) . '/Lobbyist/Lobbyist.php');

// Errors
require(dirname(__FILE__) . '/Lobbyist/Error.php');
require(dirname(__FILE__) . '/Lobbyist/ApiError.php');
require(dirname(__FILE__) . '/Lobbyist/AuthenticationError.php');
require(dirname(__FILE__) . '/Lobbyist/InvalidRequestError.php');

// Plumbing
require(dirname(__FILE__) . '/Lobbyist/ApiRequestor.php');
require(dirname(__FILE__) . '/Lobbyist/ApiResource.php');

// Lobbyist API Resources
require(dirname(__FILE__) . '/Lobbyist/Contact.php');
