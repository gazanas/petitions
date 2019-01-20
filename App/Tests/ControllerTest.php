<?php

namespace App\Tests;

use App\Controllers\IndexController;
use App\Controllers\PetitionController;
use App\Models\Votes;
use App\Src\Request;

class ControllerTest extends DatabaseTestCase
{
    private $controller;
    private $database;
    
    public function setUp()
    {
        $this->request = $this->getMockBuilder(Request::class)->getMock();
        $this->database = $this->getDatabaseConnection();
        $this->createSchema($this->database);
        $this->populateSchema($this->database);
    }
    
    public function tearDown()
    {
        $this->destroySchema($this->database);
    }
    
    public function testIsEmptyInput()
    {
        
        /**
         * Test if no input is given
         */
        $this->assertTrue((new IndexController($this->request, $this->database))->isEmpty(['title', 'goal', 'image', 'summary'], []));
        
        /**
         * Test if input is missing
         */
        $this->assertTrue((new IndexController($this->request, $this->database))->isEmpty(['title', 'goal', 'image', 'summary'], ['title' => 'Petition1', 'goal' => 150, 'summary' => 'Petition Summary']));
        
        /**
         * Test if input is correct
         */
        $this->assertFalse((new IndexController($this->request, $this->database))->isEmpty(['title', 'goal', 'image', 'summary'], ['title' => 'Petition1', 'goal' => 150, 'image' => 'petition.jpg', 'summary' => 'Petition Summary']));
    }
    
    public function testGetPetitions()
    {
        $expected = json_encode([
          [
              'id' => '1',
              'title' => 'Test Poll',
              'image' => 'test.jpg',
              'summary' => 'test summary',
          ]  
        ]);
        
        $this->expectOutputString($expected);
        
        (new IndexController($this->request, $this->database))->get();
    }
    
    public function testGetPetitionThatDoesntExist()
    {
        $expected = json_encode([
        ]);
        
        $this->expectOutputString($expected);
        
        (new PetitionController($this->request, $this->database))->getPetition(2);
    }
    
    public function testGetOnePetition()
    {
        $expected = json_encode([
            'id' => '1',
            'title' => 'Test Poll',
            'image' => 'test.jpg',
            'summary' => 'test summary',
        ]);
        
        $this->expectOutputString($expected);
        
        (new PetitionController($this->request, $this->database))->getPetition(1);
    }
    
    public function testCreateNewPetitionMissingAllInputsFails()
    {
        $this->request->expects($this->any())->method('all')->willReturn([]);
        
        /**
         * Missing all input fields fails
         */
        $expected = json_encode([
            'error' => true,
            'type' => 'title-error',
            'message' => 'Please Complete Title'
        ]);
               
        $this->expectOutputString($expected);
        $this->assertNull((new IndexController($this->request, $this->database))->add());
        
    }
    
    public function testCreateNewPetitionMissingOneInputFails()
    {
        $this->request->expects($this->any())->method('all')->willReturn(['id' => '1',
                                                                        'image' => 'test.jpg',
                                                                        'goal' => 150,
                                                                        'summary' => 'test summary']);
        
        /**
         * Missing one input field fails
         */
        $expected = json_encode([
            'error' => true,
            'type' => 'title-error',
            'message' => 'Please Complete Title'
        ]);
                
        $this->expectOutputString($expected);
        $this->assertNull((new IndexController($this->request, $this->database))->add());
        
    }
    
    public function testCreateNewPetition()
    {  
        $this->request->expects($this->any())->method('all')->willReturn(['id' => '1',
                                                                        'title' => 'Test Poll',
                                                                        'image' => 'test.jpg',
                                                                        'goal' => 150,
                                                                        'summary' => 'test summary']);
        
        /**
         * Successful creation
         */
        $expected = json_encode([
            'success' => true
        ]);
        
        $this->expectOutputString($expected);
        
        (new IndexController($this->request, $this->database))->add();
    }
    
    /**
     * Slows the tests down
     * 
    public function testGetNewVotesTimesOutAfterTenSeconds()
    {
        $expected = json_encode([
            'timeout' => true
        ]);
        
        $this->expectOutputString($expected);
        
        (new PetitionController($this->database, 1))->handleGetRequest('get_new_votes', []);
    }
    */
    
    public function testGetVotesOfPollNoVotesExist()
    {
        $expected = json_encode([
            'votes' => 0
        ]);
                
        $this->expectOutputString($expected);
        
        (new PetitionController($this->request, $this->database))->getVotes(1);
    }
    
    public function testGetVotesOfPoll()
    {
            
        $expected = json_encode([
            'votes' => round((1/150)*100, 2), 
            'last' => 'Test', 
            'country' => 'United States'
        ]);
       
        (new Votes($this->database))->vote(1, 'Test', 'test2@example.com', 'United States');
        
        $this->expectOutputString($expected);
        
        (new PetitionController($this->request, $this->database))->getVotes(1);
    }
    
    public function testAddNewVoteMissingAllInputsFails()
    {
        $this->request->expects($this->any())->method('all')->willReturn([]);
        
        /**
         * Missing one input field fails
         */
        $expected = json_encode([
            'error' => true,
            'type' => 'name-error',
            'message' => 'Please Complete Name'
        ]);
        
        $this->expectOutputString($expected);
        (new PetitionController($this->request, $this->database))->vote(1);
        
    }
    
    public function testAddNewVoteMissingOneInputFails()
    {
        $this->request->expects($this->any())->method('all')->willReturn(['pid' => 1, 'name' => 'Test', 'country' => 'United States']);
        /**
         * Missing one input field fails
         */
        $expected = json_encode([
            'error' => true,
            'type' => 'email-error',
            'message' => 'Please Complete Email'
        ]);
        
        $this->expectOutputString($expected);
        (new PetitionController($this->request, $this->database))->vote(1);
        
    }
    
    public function testAddNewVoteSuccess()
    {
        $this->request->expects($this->any())->method('all')->willReturn(['pid' => 1, 'name' => 'Test', 'email' => 'test@example.com', 'country' => 'United States']);
        /**
         * Missing one input field fails
         */
        $expected = json_encode([
            'success' => true
        ]);
        
        $this->expectOutputString($expected);
        (new PetitionController($this->request, $this->database))->vote(1);
    }
}