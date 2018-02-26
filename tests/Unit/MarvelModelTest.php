<?php

namespace Tests\Unit;

use \PHPUnit\Framework\TestCase as TestCase;
use App\Models\Marvel as Marvel;

class MarvelModelTest extends TestCase
{
    /**
     * Test that Marvel->getCharacterID() function returns an integer for valid parameters.
     *
     * @return void
     */
    public function testMarvelReturnsIntTest()
    {
    	$marvel = new Marvel();
		$char_id = $marvel->getCharacterID('Spider-Man');
        $this->assertInternalType('int', $char_id);
    }

    /**
     * Test that Marvel->getItemsForCharacter() function returns an array for valid parameters.
     *
     * @return void
     */
    public function testMarvelReturnsArrayTest()
    {
    	// Spider-Man has ID = 1009610
    	$marvel = new Marvel();
		$results = $marvel->getItemsForCharacter('Spider-Man', 'comics', 1009610);
        $this->assertInternalType('array', $results);
    }


}
