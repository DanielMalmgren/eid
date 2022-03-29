<?php

namespace App\Models;

class User
{
    public $name;
    public $username;
    public $title;
    public $organization;
    public $organizations;
    public $personid;
    public $isAdmin;

    public function __construct(String $username)
    {
        $aduser = \LdapRecord\Models\ActiveDirectory\User::where('sAMAccountName', $username)->first();
        $adgroup = \LdapRecord\Models\ActiveDirectory\Group::find(env('ADMIN_GROUP'));

        $this->username = $username;
        if(isset($aduser)) {
            $this->name = $aduser->displayName[0];

            if(isset($aduser->title)) {
                $this->title = mb_substr($aduser->title[0], 0, 22);
            } else {
                $this->title = "Fel på titel";
                logger("Missing title for user ".$username);
            }
            $this->organization = $aduser->company[0];
            $this->personid = $aduser->employeeID[0];
            $this->isAdmin = $aduser->groups()->recursive()->exists($adgroup);

            $this->organizations = [];
            $orgGroups  = json_decode(env('ORG_GROUPS'), true);
            $groups = $aduser->groups();
            foreach($orgGroups as $org => $group) {
                $orgGroup = \LdapRecord\Models\ActiveDirectory\Group::find($group);
                if($groups->exists($orgGroup)) {
                    $this->organizations[] = $org;
                }
            }
        }
    }
}
