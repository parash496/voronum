<?php

namespace Url\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\DatabaseManager as DB;

class UserRegisteration extends Model
{
	protected $database;

    function __construct(DB $db)
    {
    	$this->database = $db;
    }

    /**
     * Makes entry of new user in the database
     *
     * @param string  $name
     * @param string  $email
     * @param string  $password
     *
     * @return void
     */
    public function makeEntry($name, $email, $password)
    {
    	$entry = $this->database
    	         ->table('users')
    	         ->insert(
                        [
    	         		    'user_name'=>$name,
    	         		    'email'=>$email,
    	         		    'password'=>$password
                        ]
    	            );             
    }
    
    /**
     * Update the api key in database after api registeration
     * 
     * @param string $name
     * @param string $email
     * @param string $apiKey
     *
     * @return void
     */ 
    public function makeApiKeyEntry($name, $email, $apiKey)
    {
        $entry = $this->database
                 ->table('users')
                 ->where(
                        [
                            'user_name' => $name,
                            'email' => $email
                        ]
                    )
                 ->update(['api_key' => $apiKey]);
    }

    /**
     * Checking the details of authentic user
     *
     * @param string  $email
     * @param string  $password
     *
     * @return array
     */
    public function getDetail($email, $password)
    {
    	$detail = $this->database
    	          ->table('users')
                  ->select('id','user_name')
                  ->where(
                        [
                            'email' => $email,
                            'password' => $password
                        ]
                    )
                  ->first();
            
    	return $detail;         
    }

    /**
     * Get the api key related to the user id
     *
     * @param string $userId
     *
     * @return Array 
     */
    public function getApiDetail($userId)
    {
        $detail = $this->database
                  ->table('users')
                  ->select('api_key')
                  ->where('id',$userId)
                  ->first();    
        return $detail;         
    }            	         
}
