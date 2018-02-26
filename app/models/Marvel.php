<?php

namespace App\Models;

use GuzzleHttp\Client;

class Marvel
{
	public function __construct()
	{
		// some parameters that are required for the GET requests
		$this->public_key = '';
		$this->private_key = '';
		$this->unix_time = time();
		$this->md5_hash = md5($this->unix_time . $this->private_key . $this->public_key);

		// Guzzle
		$this->client = new Client(['http_errors' => false]);
		$this->api_host = 'https://gateway.marvel.com:443/v1/public/characters';
	}


	/**
	 * Make a call to the Marvel API to get the character ID.
	 * details from https://developer.marvel.com/documentation/authorization
	 *
	 * @return integer
	 */
	public function getCharacterID($name)
	{
		// GET request to Marvel API for the character ID
		$char_response = $this->client->get($this->api_host, 
			['query' => ['ts' => $this->unix_time, 'apikey' => $this->public_key, 'hash' => $this->md5_hash, 'name' => $name]
		]);
		$char_result = json_decode($char_response->getBody()->getContents(), true);

		$char_id = 0;
		if($char_result['data']['total'] > 0){
			$char_id = $char_result['data']['results'][0]['id'];
		}

		return $char_id;
	}

	/**
	 * Make a call to the Marvel API for the character and type of list we are interested in.
	 *
	 * @return array
	 */
	public function getItemsForCharacter($name, $type, $char_id)
	{
		// the url has additional sectors added to the path
		$url_extension = '/' . $char_id . '/' . $type;
		$list_url_string = $this->api_host . $url_extension;

		// GET request to Marvel API for the comics, events, series, stories
		$list_response = $this->client->get($this->api_host . $url_extension, 
			['query' => ['ts' => $this->unix_time, 'apikey' => $this->public_key, 'hash' => $this->md5_hash, 'limit' => 40]
		]);
		$list_result = json_decode($list_response->getBody()->getContents(), true);

		// populate the results array that will be used to output to the CSV file
		$results = array();
		$results[] = ['character','type','name','description','date'];
		foreach($list_result['data']['results'] as $list_item){
			// the date can be of a different format depending on the type
			$date = '';
			switch ($type) {
				case 'comics':
					$date = $list_item['dates'][0]['date'];
				break;
				case 'events':
					$date = $list_item['start'];
				break;
				case 'series':
					$date = $list_item['startYear'];
				break;
				case 'stories':
					//$date = '';
				break;
			}

			$results[] = [$name,
						  $type,
						  $list_item['title'],
						  $list_item['description'],
						  $date,
						 ];
		}

		return $results;
	}
}

