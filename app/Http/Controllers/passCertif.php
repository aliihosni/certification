<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use PDF;



class passCertif extends Controller
{
    public $res = array();
    public $questions= array();
    public $duree;
    public $subject;

    public function __construct() {
        // Field Access of Listing Columns
        if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
            $this->middleware(function ($request, $next) {
                return $next($request);
            });
        }
    }

    public function index()
    {

            return redirect(config('laraadmin.adminRoute')."/");

    }
    public function show($id){


        /*var_dump($values[0]);
        die();*/
        if($this->init($id)) {

            return View('la.passCertif.index', [
                'id'=>$id,
                'duree' => $this->duree,
                'subject' => $this->subject,
                'questions' => $this->questions,
                'res' => $this->res
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    public function getsubject($id){

        $values = DB::table('certifs')->where('id', $id)->get();

        if($values[0]->user == Auth::user()->id && !$values[0]->status) {

            $certif = DB::table('certifications')->where('id', $values[0]->certification)->get();
            $this->duree = $certif[0]->duration;
            $this->subject = $values[0]->subject;
            $this->questions = DB::table('questions')->whereNull('deleted_at')->where('subject', $this->subject)->get();


            foreach ($this->questions as $question){
                $respons = DB::table('responses')->whereNull('deleted_at')->where('question', $question->id)->get();
                //array_push($res, $respons);
                $this->res[$question->id] = $respons;
            }
            //
            $data = [
                'id'=>$id,
                'duree' => $this->duree,
                'subject' => $this->subject,
                'questions' => $this->questions,
                'res' => $this->res
            ];
            $pdf = PDF::loadView('la.passCertif.subject', $data);
            return $pdf->stream('correction.pdf');

        }
        return redirect(config('laraadmin.adminRoute')."/");
    }


    public function init($id){
        $values = DB::table('certifs')->where('id', $id)->get();

        if($values[0]->user == Auth::user()->id && $values[0]->status) {

            $certif = DB::table('certifications')->where('id', $values[0]->certification)->get();
            $this->duree = $certif[0]->duration;
            $this->subject = $values[0]->subject;
            $this->questions = DB::table('questions')->whereNull('deleted_at')->where('subject', $this->subject)->get();

            shuffle($this->questions);

            foreach ($this->questions as $question){
                $respons = DB::table('responses')->whereNull('deleted_at')->where('question', $question->id)->get();
                shuffle($respons);
                //array_push($res, $respons);
                $this->res[$question->id] = $respons;
            }
            return true;
        }
        return false;
    }

    public function update(Request $request, $id)
    {
        $score=0;
        if ($this->init($id)){
            foreach($this->questions as $question){
                $i=0;
                foreach($this->res[$question->id] as $r){
                    if (isset($request[$r->id])) {
                        if ($r->type==="Correct"){
                            $i++;
                        }else{
                            $i=-50;
                        }

                    }
                    else{
                        if ($r->type==="Incorrect"){
                            $i++;

                        }else{
                            $i=-50;
                        }
                    }
                }
                if($i>0){
                    $score+=1;
                }
            }
            DB::table('certifs')->where('id', $id)->update(array('score' => $score,'total'=>count($this->questions),'status'=>0));
            return redirect()->route(config('laraadmin.adminRoute') . '.certifs.index');

        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

}
