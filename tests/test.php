#!/usr/bin/php -qC
<?
/**
* Testing script for PHP-EnvFile
*/

chdir(dirname(__FILE__));
require('../envfile.php');

// ok - Expected pass
$got = envfile('ok/.env', 'ok/.env.example');
$want = array('foo' => 'fooValue', 'bar' => 'barValue', 'baz' => 'bazValue', 'quz' => 'quzValue');
$pass = serialize($got) == serialize($want);
echo ($pass ? 'OK  ' : 'FAIL') . " - Ok test\n";


// fail-missing - Fail because of missing file
ob_start();
$got = envfile('fail-missing/.env', 'fail-missing/.env.example', FALSE);
$output = ob_get_contents();
ob_end_clean();

$want = array();
$pass = serialize($got) == serialize($want);
echo ($pass ? 'OK  ' : 'FAIL') . " - Expected fail empty - Missing file but still returns blank array\n";
echo (preg_match('/file not found/is', $output) ? 'OK  ' : 'FAIL') . " - Expected fail - raised file not found error\n";


// fail-examples - Fail because of missing settings in the .env.settings file but not in .env
ob_start();
$got = envfile('fail-examples/.env', 'fail-examples/.env.example', FALSE);
$output = ob_get_contents();
ob_end_clean();

$want = array('foo' => 'fooValue', 'baz' => 'bazValue', 'quz' => 'quzValue');
$pass = serialize($got) == serialize($want);
echo ($pass ? 'OK  ' : 'FAIL') . " - Expected fail - omitted examples test, still returns array\n";
echo (preg_match('/missing settings/is', $output) ? 'OK  ' : 'FAIL') . " - Expected fail - raised error\n";
