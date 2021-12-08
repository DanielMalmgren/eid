<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\FrejaAPI;

class HomeController extends Controller
{
    use FrejaAPI;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('authnodb');
    }

    private function getUser(Request $request)
    {
        $user = session()->get('user');
        if($user->isAdmin && $request->username !== null) {
            return new User($request->username);
        } else {
            return session()->get('user');
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = session()->get('user');
        $asuser = $this->getUser($request);

        $data = [
            'user' => $user,
            'asuser' => $asuser,
        ];

        return view('home')->with($data);
    }

    public function logout()
    {
        session()->flush();
        return view('logout');
    }

    public function orgid(Request $request)
    {
        $user = session()->get('user');
        $asuser = $this->getUser($request);

        if($request->has('title')) {
            $asuser->title = $request->title;
        }

        if($request->has('organization')) {
            $asuser->organization = $request->organization;
        }

        $reference = $this->addOrgId($asuser);

        if($reference === null) {
            return view('orgid/failure');
        }

        $data = [
            'user' => $user,
            'asuser' => $asuser,
            'reference' => $reference,
        ];

        return view('orgid/success')->with($data);
    }

    public function orgidResult(Request $request) {
        return $this->getOneResult($request->organization, $request->reference);
    }
}
