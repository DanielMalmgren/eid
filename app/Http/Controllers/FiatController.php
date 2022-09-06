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
        $hasOrgId=null;
        $user = new User($targetuser);
        if(isset($user) && isset($user->name)) {
            logger(print_r($user, true));
            $hasOrgId = $this->checkForOrgId($user);
        } else {
            logger("Trying to auth non existing user: ".$targetuser);
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
