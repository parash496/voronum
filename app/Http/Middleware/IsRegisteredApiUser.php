<?php

namespace Url\Http\Middleware;

use Closure;
use Url\Models\ApiModel;

class IsRegisteredApiUser
{
    protected $apiDetails;

    function __construct(ApiModel $api)
    {
        $this->apiDetails = $api;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKey = $request->apiKey;
        $sessionKey = $request->sessionKey;
        $getSessionUserId = $this->apiDetails->sessionDetail($sessionKey);
        $getApiUserId = $this->apiDetails->getApiKeyUserDetail($apiKey);
        if(!$getApiUserId) {
           return "Invalid key";
        }    
        if($getSessionUserId->active == 1)
        {
            $sessionUserId = $getSessionUserId->user_id;
            $apiUserId = $getApiUserId->user_id;
            if($sessionUserId == $apiUserId)
            {
                return $next($request);
            } else {
                return "error";
            }
        } else {
            return "Session Key not Active";
        }        
    }
}
