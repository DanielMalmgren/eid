<?php

namespace App\Traits;
use App\Models\User;

use Illuminate\Support\Facades\Http;

trait FrejaAPI {

    static $baseurl = "https://services.prod.frejaeid.com/organisation/management/orgId/1.0/";

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

        $url = self::$baseurl . "initAdd";
        $relyingPartyId = "&relyingPartyId=id_itsam01_" . strtr_utf8(mb_strtolower($user->organization), "åäö", "aao");
        $content = "initAddOrganisationIdRequest=" . $parameterJson . $relyingPartyId;
logger($relyingPartyId);
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

        $url = self::$baseurl . "getOneResult";
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
