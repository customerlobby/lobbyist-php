<?php

echo "Running the Lobbyist PHP bindings test suite.\n".
     "If you're trying to use the Lobbyist PHP bindings you'll probably want ".
     "to require('lib/Lobbyist.php'); instead of this file\n";

function authorizeFromEnv()
{
  Lobbyist::setApiKey(getenv('LOBBYIST_API_KEY'));
  Lobbyist::setApiSecret(getenv('LOBBYIST_API_SECRET'));
}

$ok = @include_once(dirname(__FILE__).'/simpletest/autorun.php');
if (!$ok)
{
  $ok = @include_once(dirname(__FILE__).'/../vendor/vierbergenlars/simpletest/autorun.php');
}
if (!$ok)
{
  echo "MISSING DEPENDENCY: The Lobbyist API test cases depend on SimpleTest. ".
       "Download it at <http://www.simpletest.org/>, and either install it ".
       "in your PHP include_path or put it in the test/ directory.\n";
  exit(1);
}

// Throw an exception on any error
function exception_error_handler($errno, $errstr, $errfile, $errline)
{
  throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler('exception_error_handler');
error_reporting(E_ALL | E_STRICT);

require_once(dirname(__FILE__) . '/../lib/Lobbyist.php');

require_once(dirname(__FILE__) . '/Lobbyist/TestCase.php');

require_once(dirname(__FILE__) . '/Lobbyist/ApiRequestorTest.php');
require_once(dirname(__FILE__) . '/Lobbyist/AuthenticationErrorTest.php');
require_once(dirname(__FILE__) . '/Lobbyist/ContactTest.php');
require_once(dirname(__FILE__) . '/Lobbyist/InvalidRequestErrorTest.php');
