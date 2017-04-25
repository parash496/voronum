<?php

namespace Url\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\DatabaseManager as DB;

class ApiModel extends Model
{
    protected $database;

    function __construct(Db $db)
    {
    	$this->database = $db;
    }

    /**
     * Enter the details related to the api key for user in database
     *
     * @param string $role
     * @param string $company
     * @param string $app_name
     * @param string $app_url
     * @param string $app_descrip
     * @param string $apiKey
     *
     * @return void
     */
    public function apiDetailEntry($role, $company, $app_name, $app_url, $app_descrip, $apiKey)
    {
    	$entry = $this->database
    	         ->table('api_details')
    	         ->insert(
                        [
    	         		    'user_id'=>session('id'),
    	         		    'role'=>$role,
                            'company'=>$company,
                            'application_name'=>$app_name,
                            'application_url'=>$app_url,
                            'application_description'=>$app_descrip,
                            'api_key'=>$apiKey
                        ]
    	            );  
    }

    /**
     * Gets the api key related to the user
     *
     * @param string $userId
     *
     * @return Array
     */
    public function getApiDetails($userId)
    {
    	$apiKeyDetail = $this->database
    			  ->table('api_details')
    			  ->select('api_key')
                  ->where('user_id',$userId)
                  ->first();
    	return $apiKeyDetail;
    }

    /**
     * Update the api key for user
     *
     * @param string $apiKey
     *
     * @return void
     */
    public function updateApiKey($apiKey, $userId)
    {
    	$updateKey = $this->database
    				 ->table('api_details')
    				 ->where('user_id',$userId)
    				 ->update(['api_key' => $apiKey]);
    }

    /**
     * Insert the user id session key and expiry date related to the user
     *
     * @param string $sessionKey
     *
     * @return void
     */
    public function enterSessionKey($userId, $sessionKey)
    {
    	$date = new DateTime;
        $expiry = $date->modify('+1 day');

    	$enterSessionKey = $this->database
    					   ->table('session_key')
    					   ->insert(
    					   		[
    					   			'user_id' => $userId,
    					   			'session_key' => $sessionKey,
    					   			'expiry' => $expiry
    					   		]

    					   	);
    }

    /**
     * Update the session key for a user
     *
     * @param string $sessionKey
     *
     * @return void
     */
    public function updateSessionKeyState($sessionKey)
    {
     	$sessionKeyStateUpdate = $this->database
    			  				->table('session_key')
                  				->where('session_key',$sessionKey)
                  				->update(['active' => 0]);
    }

    /**
     * Gets the user id related to the session key for user
     *
     * @param string $sessionKey
     *
     * @return Array
     */
    public function sessionDetail($sessionKey)
    {
    	$sessionKeyUserDetail = $this->database
    			  				->table('session_key')
    			  				->select('user_id','active')
                  				->where('session_key',$sessionKey)
                  				->first();              				
    	return $sessionKeyUserDetail;
    }

    /**
     * Gets the user id related to the api key for user
     *
     * @param string $apiKey
     *
     * @return Array
     */
    public function getApiKeyUserDetail($apiKey)
    {
    	$apiKeyUserDetail = $this->database
    			  			->table('api_details')
    			  			->select('user_id')
                  			->where('api_key',$apiKey)
                  			->first();
    	return $apiKeyUserDetail;
    }
}
