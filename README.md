# Installation

Get the latest version of the Customer Lobby PHP bindings with:

    git clone https://github.com/customerlobby/lobbyist-php

Add the following to your PHP script:

    require_once("/path/to/lobbyist-php/lib/Lobbyist.php");

Simple usage looks like:

    Lobbyist::setApiKey('d8e8fca2dc0f896fd7cb4cb0031ba249');
    Lobbyist::setApiSecret('L0h8d4jfusld893jflsJfjsYjdl3938urLj01qkd9Rsa4IjdnNs9ljreijoi4498resIjDflKdj');
    $params = array('first_name' => 'John', 'last_name' => 'Doe, 'daytime_phone' => '123-987-0192', 'email' => 'jdoe@somewhere.com');
    $contact = Lobbyist_Contact::create($params);
    echo $contact;

## Documentation

Please see ### API DOCUMENTATION URL ### for up-to-date documentation.

## Tests

In order to run tests you have to install [SimpleTest](http://www.simpletest.org/). Download the Simpletest code, then
place the _simpletest_ folder below the _/path/to/lobbyist-php/test_ folder.

Run test suite:

    php ./test/Lobbyist.php