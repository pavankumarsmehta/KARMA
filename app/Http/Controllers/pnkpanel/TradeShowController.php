<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tradeshow;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use File;

class TradeShowController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return Tradeshow::class;
    }
    
    public function list(Request $request) {
		
		if (request()->ajax()) {
            $model = Tradeshow::select('*');
            $table = DataTables::eloquent($model);
            $table->addIndexColumn();
            $table->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->treadeshow_id . '" />';
            });
            $table->editColumn('treadeshow_name', function ($row) {
                /*if($row->name == 'contact-us')
				{
					return "<a href=" . URL('/') . '/pnkpanel/brand/edit' . $row->brand_id.".html". " target='_blank'>" . $row->brand_name .".html". "</a>";
				}
				else
				{*/
					return "<a href=" . URL('/') . '/pnkpanel/trade-show/edit/' . $row->treadeshow_id."". ">" . $row->treadeshow_name ."". "</a>";
				//}
            });
            $table->editColumn('status', function ($row) {
                return ($row->status ? 'Active' : 'Inactive');
            });
            $table->editColumn('action', function ($row) {
                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->treadeshow_id]);
                return $action;
            });
            $table->rawColumns(['checkbox', 'action', 'treadeshow_name']);
            return $table->make(true);
        }

        $pageData['page_title'] = "Trade Show Page";
        $pageData['meta_title'] = "Trade Show Page";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Trade Show Page',
                'url' => route('pnkpanel.trade-show.list')
            ]
        ];
        return view('pnkpanel.trade_show.list')->with($pageData);
	}
	
	public function edit($id = 0) {
        if($id > 0) {
			$tradeshow = Tradeshow::findOrFail($id);
		} else {
			$tradeshow =  new Tradeshow;
		}
		
		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Trade Show';
		$pageData['meta_title'] = $prefix.' Trade Show';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Trade Show List',
				 'url' =>route('pnkpanel.trade-show.list')
			 ],
			 [
				 'title' => $prefix.' Brand',
				 'url' =>route('pnkpanel.trade-show.edit', $id)
			 ]
		];
		
        return view('pnkpanel.trade_show.edit', compact('tradeshow'))->with($pageData);;
    }
	
    public function update(Request $request) {
		$actType = $request->actType;
		$treadeshow_id = $request->treadeshow_id;
		$is_delete = $request->is_delete;
		$del_chk = $request->del_chk;
		$del_chk_arr = explode(",",$del_chk);
		
		if($actType == 'add') {
			$check_duplicate = Tradeshow::where('treadeshow_name', $request->treadeshow_name)->first();
		} else {
			$check_duplicate = Tradeshow::where('treadeshow_name', $request->treadeshow_name)->where('treadeshow_id', '<>', $treadeshow_id)->first();
		}
		if($check_duplicate)
		{
			session()->flash('site_common_msg_err', 'Tradeshow name already exists. Please change it');
			return redirect()->route('pnkpanel.trade-show.edit', $treadeshow_id);
		}

		$this->validate($request, [
			'treadeshow_name'	=> 'required|string',
			'address1'	=> 'required|string',
			'city'	=> 'required|string',
			'state'	=> 'required|string',
			'country'	=> 'required|string',
			'zip'	=> 'required|numeric',
			'display_position'=> 'required|numeric'
		]);
	
		$tradeshow = Tradeshow::findOrNew($treadeshow_id);
		if ($request->hasFile('tradeshow_logo')) {
			if ($request->file('tradeshow_logo')->isValid()) {
				$data = $this->validate($request, [
					'tradeshow_logo' => 'required|mimes:jpeg,png,webp,jpg',
				]);
			}
		}
		
		$tradeshow->treadeshow_name =  $request->treadeshow_name;
		$tradeshow->booth_no 	=  (isset($request->booth_no) && !empty($request->booth_no)) ? $request->booth_no : '';
		//$brand->display_menu_position 	=  (empty($request->display_menu_position) AND trim($request->display_menu_position) == '') ?  99999 : $request->display_menu_position;
		if(isset($request->date1) && !empty($request->date1)){
			$tradeshow->date1 = date('Y-m-d',strtotime($request->date1));
			$tradeshow->from_time1 = $request->from_time1;
			$tradeshow->to_time1 = $request->to_time1;
		}else{
			$tradeshow->date1 = NULL;
			$tradeshow->from_time1 = NULL;
			$tradeshow->to_time1 = NULL;
		}

		if(isset($request->date2) && !empty($request->date2)){
			$tradeshow->date2 =  date('Y-m-d',strtotime($request->date2));
			$tradeshow->from_time2 = $request->from_time2;
			$tradeshow->to_time2 = $request->to_time2;
		}else{
			$tradeshow->date2 = NULL;
			$tradeshow->from_time2 = NULL;
			$tradeshow->to_time2 = NULL;
		}

		if(isset($request->date3) && !empty($request->date3)){
			$tradeshow->date3 =  date('Y-m-d',strtotime($request->date3));
			$tradeshow->from_time3 = $request->from_time3;
			$tradeshow->to_time3 = $request->to_time3;
		}else{
			$tradeshow->date3 = NULL;
			$tradeshow->from_time3 = NULL;
			$tradeshow->to_time3 = NULL;
		}
		if(isset($request->date4) && !empty($request->date4)){
			$tradeshow->date4 =  date('Y-m-d',strtotime($request->date4));
			$tradeshow->from_time4 = $request->from_time4;
			$tradeshow->to_time4 = $request->to_time4;
		}else{
			$tradeshow->date4 = NULL;
			$tradeshow->from_time4 = NULL;
			$tradeshow->to_time4 = NULL;
		}
		if(isset($request->date5) && !empty($request->date5)){
			$tradeshow->date5 =  date('Y-m-d',strtotime($request->date5));
			$tradeshow->from_time5 = $request->from_time5;
			$tradeshow->to_time5 = $request->to_time5;
		}else{
			$tradeshow->date5 = NULL;
			$tradeshow->from_time5 = NULL;
			$tradeshow->to_time5 = NULL;
		}
		if(isset($request->date6) && !empty($request->date6)){
			$tradeshow->date6 =  date('Y-m-d',strtotime($request->date6));
			$tradeshow->from_time6 = $request->from_time6;
			$tradeshow->to_time6 = $request->to_time6;
		}else{
			$tradeshow->date6 = NULL;
			$tradeshow->from_time6 = NULL;
			$tradeshow->to_time6 = NULL;
		}
		if(isset($request->date7) && !empty($request->date7)){
			$tradeshow->date7 =  date('Y-m-d',strtotime($request->date7));
			$tradeshow->from_time7 = $request->from_time7;
			$tradeshow->to_time7 = $request->to_time7;
		}else{
			$tradeshow->date7 = NULL;
			$tradeshow->from_time7 = NULL;
			$tradeshow->to_time7 = NULL;
		}

		$tradeshow->appointment_url 	=  (isset($request->appointment_url) && !empty($request->appointment_url)) ? $request->appointment_url : '';
		$tradeshow->address1 	=  (isset($request->address1) && !empty($request->address1)) ? $request->address1 : '';
		$tradeshow->address2 	=  (isset($request->address2) && !empty($request->address2)) ? $request->address2 : '';
		$tradeshow->city 	=  (isset($request->city) && !empty($request->city)) ? $request->city : '';
		$tradeshow->state 	=  (isset($request->state) && !empty($request->state)) ? $request->state : '';
		$tradeshow->country 	=  (isset($request->state) && !empty($request->country)) ? $request->country : '';
		$tradeshow->zip 	=  (isset($request->zip) && !empty($request->zip)) ? $request->zip : '';

		$tradeshow->display_position 	=  (isset($request->display_position) && empty($request->display_position) AND trim($request->display_position) == '') ?  999999 : $request->display_position;
		$tradeshow->status 	=  $request->status;
		//dd($tradeshow);
		if($tradeshow->save()) {
			
			if ($request->hasFile('tradeshow_logo')) {
				if ($request->file('tradeshow_logo')->isValid()) {
					
					if(!file_exists(config('const.TRADESHOW_IMAGE_PATH'))) {
						File::makeDirectory(config('const.TRADESHOW_IMAGE_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('tradeshow_logo');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = strtolower(clearSpecialCharacters($original_filename))."_".$rand_num."_".$tradeshow->treadeshow_id.".".$image->getClientOriginalExtension();

					$image_name = $original_filename; //site_slug($brand->brand_name).'_'.$brand->brand_id.'_th'.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.TRADESHOW_IMAGE_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.'/'.$image_name;
					// $image_resize = Image::make($orig_saved_file_path);  
					// $image_resize->resize(config('const.BRAND_IMAGE_THUMB_WIDTH'), config('const.BRAND_IMAGE_THUMB_HEIGHT'));
					// $image_resize->save($orig_saved_file_path);					
					// if(isset($tradeshow->tradeshow_logo) && !empty($tradeshow->tradeshow_logo)) {
                    //     $request->actType =  'delete_image';
					// 	$request->type = 'tradeshow_logo';
					// 	$request->id =  $tradeshow->treadeshow_id;
					// 	$request->subtype = 'tradeshow_logo'; 
					// 	$request->image_name = $tradeshow->tradeshow_logo;
                    //     $this->deleteImage($request);
					// }	 

					$tradeshow = Tradeshow::find($tradeshow->treadeshow_id);
					$tradeshow->tradeshow_logo = $image_name;
					$tradeshow->save();
				}
			}
			
			if($actType == 'add') {
				session()->flash('site_common_msg', config('messages.msg_add'));
			} else {
				session()->flash('site_common_msg', config('messages.msg_update')); 
			}
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_add_err'));
		}
		return redirect()->route('pnkpanel.trade-show.edit', $tradeshow->treadeshow_id);
	}
	
	public function delete($id) {
		if($this->deleteTradeshow($id)) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.trade-show.list');
	}
	
	private function deleteTradeshow($id) {
		Tradeshow::where('treadeshow_id', $id)->delete();
		return true;
	}
	
	public function deleteImage(Request $request) {
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;
		
		$actType = $request->actType;
		
		if(in_array($actType, ['delete_image']))
		{
			//echo $request->type; exit;
			$destination_path = '';
			$destination_landing_path = '';
			if($request->type == 'tradeshow_logo') {
				$destination_path = config('const.TRADESHOW_IMAGE_PATH');
			} 
			
			//echo config('const.BRAND_IMAGE_PATH').$request->image_name; exit;
			$image_name = $request->image_name;
			//echo $destination_path.$request->image_name; exit;
			
			//echo $request->subtype; exit;
			if(File::delete($destination_path.$image_name)) {
				
				$tradeshow = Tradeshow::find($request->id);
				
				if($request->subtype == 'tradeshow_logo') {
					$tradeshow->tradeshow_logo = NULL;
				}
				
				$tradeshow->save();
				
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_delete_image")]];
				$response_http_code = 200;
			} else {
				$success = false;
				$errors = ["message" => [config("messages.msg_delete_image_err")]];
				$messages = [];
				$response_http_code = 400;
			}
	
		}
		//dd($messages);
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}
}
