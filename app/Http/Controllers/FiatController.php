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

    public function orgIdAuthResult(Request $request, String $authRef) {
        return $this->orgIdAuthResultBackend($authRef, $request->organization);
    }

    public function orgIdStartAuth(Request $request, String $targetuser) {
        $user = new User($targetuser);
        return $this->initOrgidAuthenticationBackend($user);
    }

    public function eIdAuthResult(Request $request, String $authRef) {
        return $this->eIdAuthResultBackend($authRef);
    }

    public function eIdStartAuth(Request $request, String $targetuser) {
        $user = new User($targetuser);
        return $this->initeidAuthenticationBackend($user);
    }
}
