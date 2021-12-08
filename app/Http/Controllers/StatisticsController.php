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
            'Kinda' => 0,
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
        ];

        return view('statistics.index')->with($data);
    }
}
