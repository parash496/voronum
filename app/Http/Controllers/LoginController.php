<?php

namespace Url\Http\Controllers;

use Url\Helper\Common;
use Url\Http\Requests\ValidateUrl;
use Url\Models\LinksModel;
use Illuminate\Http\Request;
use Url\Models\UserRegisteration;

class LoginController extends Controller
{
    protected $loginEntry;
    protected $userDetail;
    protected $getUserLinks;
    protected $commonFunction;
    
    function __construct(Request $request, UserRegisteration $user, LinksModel $links, Common $common)
    {
    	$this->userDetail = $user;
        $this->loginEntry = $request;
        $this->getUserLinks = $links;
        $this->commonFunction = $common;
    }

    /**
     * Used for authenticating the user
     *
     * @return Illuminate\Http\Response
     */
    public function auth()
    {
        $email = $this->loginEntry->input('email');
        $password = $this->commonFunction->generateHash($this->loginEntry->input('pass'));
        
        $info = $this->userDetail->getDetail($email, $password);
        if (count($info) == 1) 
        {
            $this->commonFunction->setSession($info->id, $info->user_name);
            return redirect('/dashboard'); 
        } 
        echo '<script language="javascript">';
        echo 'alert("Username/Password does not match.")';
        echo '</script>';
        return redirect('/login');
    }
    
    /**
     * Register a new user
     *
     * @return Illuminate\Http\Response
     */
    public function register()
    {
        $name = $this->loginEntry->input('uname');
        $email = $this->loginEntry->input('email');
        $password = $this->commonFunction->generateHash($this->loginEntry->input('pass'));
        $this->userDetail->makeEntry($name, $email, $password);
        
        $info = $this->userDetail->getDetail($email, $password);
        
        $this->commonFunction->setSession($info->id, $info->user_name);
        
        return redirect('/dashboard');
    }
    
    /**
     * Sign out a user
     *
     * @return redirect
     */
    public function signout()
    {
        session()->flush();
        return redirect('/login');
    }

    /**
     * Display the registeration form page
     *
     * @return Illuminate\Http\Response
     */
    public function registerView()
    {
        return view('register');
    }

    /**
     * Display the login form page
     *
     * @return Illuminate\Http\Response
     */
    public function home()
    {
        if($this->loginEntry->session()->has('id'))
        {
            return redirect('/dashboard');
        }
        return view('login');
    }

    /**
     * Display the main dashboard.
     *
     * @return Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Display the api register form page
     *
     * @return Illuminate\Http\Response
     */
    public function apiRegisterView()
    {
        return view('apiregister');
    }
}
