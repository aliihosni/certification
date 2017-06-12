<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
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
     * @return Response
     */
    public function index()
    {
        $certifications = DB::table('certifications')->whereNull('deleted_at')->get();
        $html='';
        foreach ($certifications as $certif){
            $cer = DB::table('certifs')->whereNull('deleted_at')->where('certification',$certif->id)->get();
            $html .= '[\''.$certif->title.'\', '. count($cer). '],';
        }
        return view('la.dashboard',[
            'html'=>$html
        ]);
    }
}