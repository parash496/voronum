<?php

namespace Url\Http\Controllers;

use Url\Helper\Common;
use Url\Models\ApiModel;
use Url\Models\LinksModel;
use Illuminate\Http\Request;
use Url\Models\UserRegisteration;
use Url\Http\Requests\ValidateUrl;

class ApiController extends Controller
{
    protected $apiRequest;
    protected $apiDetails;
    protected $userDetail;
    protected $getUserLink;
    protected $commonFunction;

    function __construct(Request $request, ApiModel $api, UserRegisteration $user, LinksModel $links, Common $common)
    {
    	$this->apiRequest = $request;
    	$this->userDetail = $user;
    	$this->apiDetails = $api;
    	$this->getUserLinks = $links;
    	$this->commonFunction = $common;
    	$this->middleware('apiRegisteredUser', ['only' => ['apiShorten','actionApi']]);
    }

    /**
     * Checks whether user is authorised for api use or not
     *
     * @return Illuminate\Http\Response
     */
    public function apiRegisterView()
    {
        $userId = session('id');
        $getApiKey = $this->apiDetails->getApiDetails($userId);
        if (count($getApiKey) == 1)
        {
        	return redirect('api/home/page=1');
        }
        return view('api-register');
    }

    /**
     * Registers a user for api use
     *
     * @return Illuminate\Http\Response
     */
    public function apiRegister()
    {
        $role = $this->apiRequest->input('role');
        $company = $this->apiRequest->input('company');
        $app_name = $this->apiRequest->input('app_name');
        $app_url = $this->apiRequest->input('app_url');
        $app_descrip = $this->apiRequest->input('app_description');
		$apiKey = $this->commonFunction->generateApiKey();

        $apiRegisteration = $this->apiDetails->apiDetailEntry(
            $role,
            $company,
            $app_name,
            $app_url,
            $app_descrip,
            $apiKey
        );

        return redirect('api/home/page=1');
    }

    /**
     * Displays the api key of user on dashboard
     *
     * @return Illuminate\Http\Response
     */
    public function apiHome()
    {
    	$userId = session('id');
    	$getApiKey = $this->apiDetails->getApiDetails($userId);
    	
    	return view('api-shorten',compact('getApiKey'));
    }

    /**
     * Generates api key for user
     *
     * @return Illuminate\Http\Response
     */
    public function apiKeyRegenerate()
    {
    	$userId = session('id');
    	$apiKey = $this->commonFunction->generateApiKey($userId);
    	$this->apiDetails->updateApiKey($apiKey, $userId);
    	return redirect('api/home/page=1');
    }

    /**
     * Logs in a user through api
     *
     * @return Json
     */
    public function apiLogin()
    {
    	$email = $this->apiRequest->input('email');
        $password = $this->commonFunction->generateHash($this->apiRequest->input('pass'));
        
        $info = $this->userDetail->getDetail($email, $password);
        if ($info) 
        {
            $this->commonFunction->setSession($info->id, $info->user_name);
            $sessionKey = $this->commonFunction->generateApiKey();
            $this->apiDetails->enterSessionKey($info->id, $sessionKey);
            $condition = 'success';
    		$httpResponse = $this->commonFunction->httpResponseMessage($condition);
            $array = [
            			'http_status' => $httpResponse['code'],
            			'http_message' => $httpResponse['message'],
            			'sessionKey' => $sessionKey->string
            		 ];
            $response = json_encode($array);
            return $response; 
        } 
    }

    /**
     * Logs out a user through api
     *
     * @return Json
     */
    public function apiLogout()
    {
     	$sessionKey = $this->apiRequest->input('sessionKey');
     	session()->flush();
     	$this->apiDetails->updateSessionKeyState($sessionKey);
     	return "Logout";
    }

    /**
     * Shortens the url through api
     * 
     * @param Url\Http\Requests\ValidateUrl $request
     *
     * @return Json
     */
    public function apiShorten()
    {
    	$url = $this->apiRequest->input('url');
    	$usersLink = $this->getUserLinks->getLinkDetail(session('id'), $url);
    	if (empty($usersLink))
        {
    		$dailyLinks = $this->getUserLinks->dailyLimitCheck(session('id'));
            if (count($dailyLinks) <= env('DAILY_LIMIT'))
            {
    			$hash = $this->commonFunction->generateHash($url);
        		$code = session('id')."-".substr($hash, 0,6);
        		if ($this->apiRequest->input('expiry')) {
        			$expiryDate = $this->apiRequest->input('expiry');
        		} else {
        			$expiryDate = 30;
        		}
        		$api = 1;
        		$this->getUserLinks->makeLinkEntry(session('id'), $url, $code, $expiryDate, $api);
        		$condition = 'success';
    			$httpResponse = $this->commonFunction->httpResponseMessage($condition);
    			$array = [
            				'http_status' => $httpResponse['code'],
            				'http_message' => $httpResponse['message'],
            				'hash' => $code,
            				'shortenedUrl' => env('URL_PATH').'/'.$code
            		 	];
            	$response = json_encode($array);
    			return $response;
    		} else {
                return "Daily Limit reached";
            }
    	}
    	return "already exists";
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
        $userId = session('id');
    	$getApiKey = $this->apiDetails->getApiDetails($userId);
        $api = 1;
        $currentPage = $i;
        $paginatedView = $this->commonFunction->paginate($currentPage, $api);

        return view('api-shorten',compact('paginatedView','currentPage','getApiKey'));      
    }

    /**
     * Change the state of link i.e. enable or disable through api dashboard
     *
     * @param string  $hash
     * @param string  $currentpage
     *
     * @return Illuminate\Http\Response
     */
    public function actionDashboard($hash, $currentPage)
    {
        $action = $this->apiRequest->input('action');
        if(isset($action)){
           switch ($action) {
                case 'enable':
                    $value = 1;
                    break;
                
                case 'disable':
                    $value = 0;
                    break;
            }
            $this->getUserLinks->updateAction($hash, $value); 
        }
        return redirect('api/home/page='.$currentPage);
    }

    /**
     * Change the state of link i.e. enable or disable through api.
     *
     * @return string
     */
    public function actionApi()
    {
        $hash = $this->apiRequest->input('hash');
        $action = $this->apiRequest->input('action');
        $currentState = $this->getUserLinks->getUrlOfHash($hash);
        if(isset($action)){
           switch ($action) {
                case 'enable':
                    if($currentState->action == 1){
                    	return "Already enaled";
                    }
                    $value = 1;
                    break;
                
                case 'disable':
                	if($currentState->action == 0){
                    	return "Already disabled";
                    }
                    $value = 0;
                    break;
            }
            $this->getUserLinks->updateAction($hash, $value); 
        }
        return "success";
    }
}
