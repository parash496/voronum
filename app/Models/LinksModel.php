<?php

namespace Url\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\DatabaseManager as DB;

class LinksModel extends Model
{
    protected $database;

    function __construct(DB $db)
    {
    	$this->database = $db;
    }

    /**
     * Makes entry in the database for all the links shortened
     *
     * @param integer  $userId
     * @param string  $getLink
     * @param string  $code
     *
     * @return void
     */
    public function makeLinkEntry($userId, $getLink, $code, $getExpiryDate, $api)
    {
    	$date = new DateTime;
        $expiry = $date->modify('+'.$getExpiryDate.' day');
        
        $entry = $this->database
    	         ->table('links')
    	         ->insert(
                        [
    	         		    'user_id'=>$userId,
    	         		    'url'=>$getLink,
                            'hash'=>$code,
                            'expiry'=>$expiry,
                            'api' => $api
                        ]
    	            );                     
    }

    /**
     * Gets all the detail related to the user and the link
     * to check if the link already exists.
     *
     * @param integer  $userId
     * @param string  $getLink
     *
     * @return Array
     */
    public function getLinkDetail($userId, $getLink)
    {
    	$detail = $this->database
                  ->table('links')
                  ->select('url','hash','redirect','action')
    	          ->where(
                        [
                            'user_id' => $userId,
                            'url' => $getLink
                        ]
                    )
                   ->first();
    	return $detail;          
    }

    /**
     * Gets all the detail related to the user to display on home page
     *
     * @param integer  $userId
     *
     * @return Array
     */
    public function getUserLinks($userId, $api)
    {
    	$linkDetail = $this->database
                      ->table('links')
    	          	  ->select('url','hash','redirect','action')
                      ->where(
                            [
                                'user_id' => $userId,
                                'api' => $api
                            ]
                        )
                      ->get();
    	return $linkDetail;          	  
	}
    
    /**
     * Gets the number of links to be showed on one page
     *
     * @param integer $userId
     * @param integer $start
     * @param integer $limit
     *
     * @return array
     */ 
    public function getUsersPerPageLink($userId, $start, $limit, $api)
    {
        $linkDetail = $this->database
                      ->table('links')
                      ->select('url','hash','redirect','action')
                      ->where( 
                            [
                                'user_id' => $userId,
                                'api' => $api
                            ]
                        )
                      ->skip($start)
                      ->take($limit)
                      ->get();
                      
        return $linkDetail;
    }

    /**
     * Gets the url and action related to the hash
     *
     * @param string  $hash
     *
     * @return Array
     */
	public function getUrlOfHash($hash)
	{
		$getUrl = $this->database
    	          	  ->table('links')
                      ->select('url','action')
    	          	  ->where('hash',$hash)
                      ->first();
    	return $getUrl;          	  
	}

    /**
     * Updates the number of redirects
     *
     * @param string  $hash
     *
     * @return void
     */
    public function updateRedirect($hash)
    {
        $redirect = $this->database
                    ->table('links')
                    ->where('hash',$hash)
                    ->increment('redirect');        
    }
                    
    /**
     * Updates the action related to a hash
     *
     * @param string  $hash
     * @param integer  $value
     *
     * @return void
     */
    public function updateAction($hash, $value)
    {
        $redirect = $this->database
                    ->table('links')
                    ->where('hash',$hash)
                    ->update(['action' => $value]);
    }

    /**
     * Check the number of url shortened daily
     *
     * @param string  $userId
     *
     * @return void
     */
    public function dailyLimitCheck($userId)
    {
        $date = new DateTime;

        $check = $this->database
                 ->table('links')
                 ->where( 
                            [
                                'user_id' => $userId,
                                'created_at' => $date
                            ]
                        )
                 ->get();
    }
}
