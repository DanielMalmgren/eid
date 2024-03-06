<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\FrejaAPI;

class StatisticsController extends Controller
{
    use FrejaAPI;

    public function __construct()
    {
        $this->middleware('authnodb');
    }

    public function index() {
        $organizations = collect([
            'Boxholm' => 0,
            'Ödeshög' => 0,
            'Ydre' => 0,
            'Åtvidaberg' => 0,
            'Vimmerby' => 0,
            'Itsam' => 0,
        ]);

        foreach($organizations as $organization => $amount) {
            $organizations[$organization] = count($this->getOrgidsPerOrganization($organization));
        }

        $data = [
            'organizations' => $organizations,
            'user' => session()->get('user'),
        ];

        return view('statistics.index')->with($data);
    }

    public function listusers(Request $request, String $municipality) {
        $user = session()->get('user');
        if(!$user->isAdmin) {
            abort(403);
        }

        $orgids = $this->getOrgidsPerOrganization($municipality);

        $data = [
            'municipality' => $municipality,
            'orgids' => $orgids,
            'count' => count($orgids),
        ];

        return view('statistics.listusers')->with($data);
    }
}
