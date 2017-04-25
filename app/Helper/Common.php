<?php

namespace Url\Helper;

use Uuid;
use Url\Models\LinksModel;

Class Common
{
	protected $linktTable;

	function __construct(LinksModel $links)
	{
		$this->linkTable = $links;
	}
   
    /**
     * Generates the hash code for the string
     *
     * @param string $string
     *
     * @return string
	 */
	public function generateHash($string)
	{
		$hash = md5($string);
		return $hash;
	}
   
    /**
     * Sets the id and name in session
     *
     * @return void
     */	
	public function setSession($id, $name)
	{
		session(
			[
				'id' => $id,
				'name' => $name
			]
		);
	}

	/**
	 * This function produces the pagination in the view
	 *
	 * @param integer $currentPage
	 *
	 * @return array
	 */
	public function paginate($currentPage, $api)
	{
		$usersLink = $this->linkTable->getUserLinks(session('id'), $api);
        $total = count($usersLink);

		$limit = 10;
        $num = ceil($total / $limit);
        if ($currentPage>$num) 
        {
        	$currentPage = $num;
        } 
        $start = ($currentPage - 1) * $limit;
        $usersPerPageLink = $this->linkTable->getUsersPerPageLink(session('id'), $start, $limit, $api);
        $count = count($usersPerPageLink);
        return [
        			'links' => $usersPerPageLink, 
        			'numOfLinks' => $count, 
        			'numOfPages' => $num
        	   ];
	}

	public function generateApiKey()
	{
		return Uuid::generate();
	}

	/**
	 * Gets the various response message and code as required
	 *
	 * @param string $condition
	 *
	 * @return Array
	 */
	public function httpResponseMessage($condition)
	{
		$x = [
            	'success' => ['code' => 200, 'message' => 'success'],
            	'created'   => ['code' => 201, 'message' => 'hash created'],
            	'failed' => ['code' => 401, 'message' => 'unauthorized']
            ];
        return $x[$condition];    
	}
}
?>


