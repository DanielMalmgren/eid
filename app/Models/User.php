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
                $this->title = mb_substr($aduser->title[0], 0, 63);
            } else {
                $this->title = "Fel på titel";
                logger("Missing title for user ".$username);
            }
            //$this->organization = $aduser->company[0];
            $this->personid = $aduser->employeeID[0];
            $this->isAdmin = $aduser->groups()->recursive()->exists($adgroup);

            $this->organizations = [];
            $orgGroups  = json_decode(env('ORG_GROUPS'), true);
            $groups = $aduser->groups();
            foreach($orgGroups as $group => $org) {
                $orgGroup = \LdapRecord\Models\ActiveDirectory\Group::find($group);
                if($groups->exists($orgGroup)) {
                    $this->organizations[] = $org;
                }
            }

            if(count($this->organizations) == 0) {
                logger("Användaren ".$username." saknar grupp för kommuntillhörighet!");
                logger("Följande grupper har hittats:");
                foreach($groups->get() as $group) {
                    logger("   ".$group->getName());
                }
            }
        }
    }
}
