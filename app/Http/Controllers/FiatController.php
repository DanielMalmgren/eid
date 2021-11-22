<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\FrejaAPI;

class FiatController extends Controller
{
    use FrejaAPI;

    public function __construct()
    {
        $this->middleware('authnodb');
    }

    public function auth(Request $request, String $targetuser)
    {
        $user = new User($targetuser);

        $response = $this->checkForOrgId($user);

        $hasOrgId = false;
        foreach (json_decode($response)->userInfos as $userinfo)
        {
            if($userinfo->organisationId->identifier == $user->username) {
                $hasOrgId = true;
                break;
            }
        }

        $data = [
            'user' => $user,
            'hasOrgId' => $hasOrgId,
        ];

        return view('fiat.auth')->with($data);
    }

    public function result(Request $request, String $authRef) {
        return $this->authResult($authRef, $request->organization);
    }

    public function orgIdAuthAjax(Request $request, String $targetuser) {
        $user = new User($targetuser);
        return $this->initOrgidAuthentication($user);
    }
}
