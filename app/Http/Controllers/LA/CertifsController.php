<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;

use App\Models\Certif;

class CertifsController extends Controller
{
	public $show_action = true;
	public $view_col = 'certification';
	public $listing_cols = ['id', 'certification', 'user', 'score', 'subject', 'status', 'total'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Certifs', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Certifs', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Certifs.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Certifs');
		
		if(Module::hasAccess($module->id)) {
			return View('la.certifs.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new certif.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created certif in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Certifs", "create")) {
		
			$rules = Module::validateRules("Certifs", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Certifs", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.certifs.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified certif.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Certifs", "view")) {
			
			$certif = Certif::find($id);
			if(isset($certif->id)) {
				$module = Module::get('Certifs');
				$module->row = $certif;
				
				return view('la.certifs.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('certif', $certif);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("certif"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified certif.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Certifs", "edit")) {			
			$certif = Certif::find($id);
			if(isset($certif->id)) {	
				$module = Module::get('Certifs');
				
				$module->row = $certif;
				
				return view('la.certifs.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('certif', $certif);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("certif"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified certif in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Certifs", "edit")) {
			
			$rules = Module::validateRules("Certifs", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Certifs", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.certifs.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified certif from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Certifs", "delete")) {
			Certif::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.certifs.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
	
	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function dtajax()
	{
        if(Auth::user()->id == 1){
            $values = DB::table('certifs')->select($this->listing_cols)->whereNull('deleted_at');
        }else{
            $values = DB::table('certifs')->select($this->listing_cols)->whereNull('deleted_at')->where('user', Auth::user()->id);
        }

		$out = Datatables::of($values)->make();
		$data = $out->getData();


		$fields_popup = ModuleFields::getModuleFields('Certifs');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/certifs/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Certifs", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/certifs/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Certifs", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.certifs.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}


                $output .= '<a href="'.url(config('laraadmin.adminRoute') . '/passCertif/'.$data->data[$i][0]).'" class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-send"></i></a>';
                $output .= '<a href="'.url(config('laraadmin.adminRoute') . '/passCertif/getsubject/'.$data->data[$i][0]).'" class="btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-file-pdf-o"></i></a>';

                $data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}
}
