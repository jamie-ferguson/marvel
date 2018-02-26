<?php

require __DIR__.'/../vendor/autoload.php';

use App\Models\Marvel as Marvel;


$types = array('comics', 'events', 'series', 'stories');
$csv_filepath = ''; // eg. /Users/jferguson/Desktop/file.csv

if(count($argv) != 3){
	// we need 2 arguments
	echo "Incorrect number of arguments." . PHP_EOL;
	die;
}elseif(!in_array($argv[2], $types)){
	// second argument must be one of 4 values
	echo "Second argument must be one of " . implode(', ', $types) . "." . PHP_EOL;
	die;
}else{
	// get the arguments
	$name 	= $argv[1];
	$type 	= $argv[2];

	// Instantiate a new Marvel instance
	// call the function to connect to Marvel API and return the character ID
	$marvel = new Marvel();
	$char_id = $marvel->getCharacterID($name);

	if($char_id == 0){
		// if the first argument is not a character available from the Marvel API then we can't proceed
		echo "Character not recognised." . PHP_EOL;
		die;
	}else{
		// call the function to connect to Marvel API and return array of results
		$results = $marvel->getItemsForCharacter($name, $type, $char_id);
		if(count($results) > 0){
			// output to csv
			$fp = fopen($csv_filepath, 'w');
			foreach ($results as $fields) {
				fputcsv($fp, $fields);
			}
			fclose($fp);
		}
	}

}