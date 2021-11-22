<?php

namespace App\Traits;
use App\Models\User;

use Illuminate\Support\Facades\Http;

trait FrejaAPI {

    static $baseurl_management = "https://services.prod.frejaeid.com/organisation/management/orgId/1.0/";
    static $baseurl_auth = "https://services.prod.frejaeid.com/organisation/authentication/1.0/";

    public function checkForOrgId(User $user) {
        logger("Kontrollerar om ".$user->name." har org-id.");

        $url = self::$baseurl_management . "users/getAll";
        $relyingPartyId = "relyingPartyId=id_itsam01_" . strtolower($user->organization);

        //$content = "getOneOrganisationIdResultRequest=" . $parameterJson . $relyingPartyId;

        $response = $this->makePostRequest($url, $relyingPartyId);

        return $response->body();
    }

    public function authResult(String $authRef, String $organization) {
        logger("Kollar status för autentisering med referens ".$authRef.".");

        $ref = array('authRef'=>$authRef);
        $refB64 = base64_encode(json_encode($ref));

        $url = self::$baseurl_auth . "getOneResult";
        $relyingPartyId = "&relyingPartyId=id_itsam01_" . strtr_utf8(mb_strtolower($organization), "åäö", "aao");
        $content = "getOneAuthResultRequest=" . $refB64 . $relyingPartyId;
        $response = $this->makePostRequest($url, $content);

        $responseCollection = $response->collect();

        logger(print_r($responseCollection, true));

        if(isset($responseCollection)) {
            return  $responseCollection['status'];
        } else {
            return null;
        }
    }

    public function initOrgidAuthentication(User $user) {
        logger("Startar org-id-autentisering för ".$user->name.".");

        $userInfo = array("country"=>"SE", "ssn"=>$user->personid);
        $userInfoB64 = base64_encode(json_encode($userInfo));

        $parameterArray = array("userInfo"=>$userInfoB64, "userInfoType"=>"SSN");
        $parameterJson = base64_encode(json_encode($parameterArray));

        $url = self::$baseurl_auth . "init";
        $relyingPartyId = "&relyingPartyId=id_itsam01_" . strtr_utf8(mb_strtolower($user->organization), "åäö", "aao");
        $content = "initAuthRequest=" . $parameterJson . $relyingPartyId;
        $response = $this->makePostRequest($url, $content);

        $responseCollection = $response->collect();

        logger(print_r($responseCollection, true));

        if(isset($responseCollection)) {
            return  $responseCollection['authRef'];
        } else {
            return null;
        }
    }

    public function addOrgId(User $user) {
        logger("Skapar org-id för ".$user->name.".");

        $userInfo = array("country"=>"SE", "ssn"=>$user->personid);
        $userInfoB64 = base64_encode(json_encode($userInfo));

        $orgidArray = array(
            "title" => $user->title,
            "identifier" => $user->username,
            "identifierName" => "Användarnamn"
        );
        $parameterArray = array(
            "userInfo" => $userInfoB64,
            "minRegistrationLevel" => "PLUS",
            "userInfoType" => "SSN",
            "organisationId" => $orgidArray
        );
        $parameterJson = base64_encode(json_encode($parameterArray));

        $url = self::$baseurl_management . "initAdd";
        $relyingPartyId = "&relyingPartyId=id_itsam01_" . strtr_utf8(mb_strtolower($user->organization), "åäö", "aao");
        $content = "initAddOrganisationIdRequest=" . $parameterJson . $relyingPartyId;
        logger("Relaying Party ID: ".$relyingPartyId);
        $response = $this->makePostRequest($url, $content);

        $responseCollection = $response->collect();
        if(isset($responseCollection)) {
            return  $responseCollection['orgIdRef'];
        } else {
            return null;
        }
    }

    public function getOneResult(User $user, String $reference) {
        $parameterArray = array(
            "orgIdRef" => $reference
        );
        $parameterJson = base64_encode(json_encode($parameterArray));

        $url = self::$baseurl_management . "getOneResult";
        $relyingPartyId = "&relyingPartyId=id_itsam01_" . strtolower($user->organization);
        $content = "getOneOrganisationIdResultRequest=" . $parameterJson . $relyingPartyId;

        $response = $this->makePostRequest($url, $content);

        return $response->body();
    }

    private function makePostRequest(String $url, String $content) {
        return Http::withOptions([
            'body' => $content,
            'verify' => false,
            'cert' => storage_path('app/private/itsam_freja_integrator.cer'), 
            'ssl_key' => storage_path('app/private/itsam_freja_integrator.key'),
        ])->bodyFormat('none')->post($url);
    }

}
