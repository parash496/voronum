<?php

namespace Url\Http\Controllers;

use Url\Helper\Common;
use Url\Models\LinksModel;
use Illuminate\Http\Request;
use Url\Http\Requests\ValidateUrl;

class LinkController extends Controller
{
    protected $request;
    protected $linkTable;
    protected $commonFunction;
     
    function __construct(Request $request, LinksModel $links, Common $common)
    {
    	$this->request = $request;
    	$this->linkTable = $links;
        $this->commonFunction = $common;
    }

    /**
     * Making entry in the database and check whether the hash
     * of particular url already exists in database
     *
     * @return Illuminate\Http\Response
     */
    public function createCode()
    {
        $getExpiryDate = $this->request->input('expiry');
        $getLink = $this->request->input('url');
    	$userId = session('id');
            	
    	$usersLink = $this->linkTable->getLinkDetail($userId, $getLink);
    	if (empty($usersLink))
        {
            if (!filter_var($getLink, FILTER_VALIDATE_URL) === false)
            {
                $hash = $this->commonFunction->generateHash($getLink);
                $code = $userId."-".substr($hash, 0,6);
                $api = 0;
        
                $this->linkTable->makeLinkEntry($userId, $getLink, $code, $getExpiryDate, $api);
            }            
            $currentPage = 1;
                   
            return redirect('/home/page='.$currentPage);     
    	}
        return redirect('/dashboard');

    }
   
    /**
     * Used for redirection and activating and deactivating
     * a hash
     *
     * @param string  $hash
     *
     * @return Redirect
     */
    public function redirect($hash)
    { 
        $getUrl = $this->linkTable->getUrlOfHash($hash);
        if($getUrl->action == 0) 
        {
            echo "Disabled";
            exit;
        }
        $redirect = $this->linkTable->updateRedirect($hash);
        return redirect($getUrl->url);
    }
    
    /**
     * Change the state of link i.e. enable or disable
     *
     * @param string  $hash
     *
     * @return Illuminate\Http\Response
     */
    public function action($hash, $currentPage)
    {
        $action = $this->request->input('action');
        if(isset($action)){
           switch ($action) {
                case 'enable':
                    $value = 1;
                    break;
                
                case 'disable':
                    $value = 0;
                    break;
            }
            $this->linkTable->updateAction($hash, $value); 
        }
        return redirect('/home/page='.$currentPage);
    }

    /**
     * Shows the next set of link in the view according
     * to page number
     *
     * @param integer $i
     *
     * @return Illuminate\Http\Response
     */ 
    public function paginatedView($i)
    {
        $api = 0;
        $currentPage = $i;
        $paginatedView = $this->commonFunction->paginate($currentPage, $api);

        return view('shorten',compact('paginatedView','currentPage'));      
    }
}
