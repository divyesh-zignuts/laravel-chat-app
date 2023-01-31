<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Conversation;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->orderBy('updated_at','DESC')->get();
		$stores = 100;
		$category = 200;
		$batch = 0;//count(DB::table('product')->select('batch_name')->distinct()->get());
		$products = 0;//count(DB::table('product')->select('boxname')->distinct()->get());		
		$store = 0;
        return view('home',compact('stores','category','batch','products','users'));
    }
	
	public function getstore()
    {
		$store = null;
		$output = [
			'data' => $store,
			'message' => "Get Store",
			'status' => "1"
		];
	    echo json_encode($output);
    }	
}
