<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeImage;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use File;

class HomePageBannerController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return HomeImage::class;
    }
    
    public function list(Request $request) {
		if(request()->ajax()) {
			$model = HomeImage::select([
				'image_id',
				'title',
				'link',
				'position',
				'banner_position',
				'display_position',
				'status',
				'home_image'
			]);
			
			if (!request()->get('order')) {
				$model->orderBy('image_id', 'asc');
			}
			
			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$row->image_id.'" />';
			});
			$table->editColumn('position', function($row) {
				//return "<input type='text' id='position_".$row->image_id."' value='".$row->position."' class='form-control input-sm' size='8'>";
				return $row->position;
			});
			
			 $table->editColumn('title', function($row) {
                //return $row->title;
                $full_name = $row->title;
                return "<a href=".route('pnkpanel.home-page-banner.edit',["id"=>$row->image_id]).">".$full_name."</a>";
            });
			$table->editColumn('status', function($row) {
				return ($row->status ? 'Active':'Inactive');
			});
			$table->editColumn('banner_position', function($row) {
				return ucwords(str_replace("_"," ",$row->banner_position));
			});
			$table->addColumn('action', function($row) {

                if($row->home_image != '') {
                    $image_src = config('const.HOME_IMAGE_URL').trim($row->home_image);
                    // $image_src = config('const.HOME_IMAGE_URL').'feature_5.jpg';
                } else {
                    $image_src = config('const.NO_IMAGE');
                }

				// return (string)view('pnkpanel.component.datatable_action', ['id' => $row->image_id]);
				return $action = '<a href="javascript:void(0);" data-toggle="tooltip" data-id="'. $row->image_id.'" data-original-title="View" title="View" class="edit view btn btn-sm btn-primary btnViewImage" data-type="viewImage" data-src="'.$image_src.'" data-caption=""><i class="bx bx-image-alt"></i></a><a href="javascript:void(0);" data-toggle="tooltip" data-id="'. $row->image_id.'" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a><a href="javascript:void(0);" data-toggle="tooltip" data-id="'. $row->image_id.'" data-original-title="Delete" title="Delete" class="delete btn btn-sm btn-danger btnDeleteRecord"><i class="bx bx-trash"></i></a>';
			});
			$table->rawColumns(['checkbox', 'title', 'position', 'banner_position', 'action']);
			return $table->make(true);
		}
		$pageData['page_title'] = "Home Page Banner Images List";
		$pageData['meta_title'] = "Home Page Banner Images List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Home Page Banner Images List',
				 'url' =>route('pnkpanel.home-page-banner.list')
			 ]
		];
		
		return view('pnkpanel.home-page-banner.list')->with($pageData);
	}
	
	public function edit($id = 0) {
        if($id > 0) {
			$home_image = HomeImage::findOrFail($id);
		} else {
			$home_image =  new HomeImage;
		}
		
		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Home Page Banner Images';
		$pageData['meta_title'] = $prefix.' Home Page Banner Images';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Home Page Banner Images List',
				 'url' =>route('pnkpanel.home-page-banner.list')
			 ],
			 [
				 'title' => $prefix.' Home Page Banner Images',
				 'url' =>route('pnkpanel.home-page-banner.edit', $id)
			 ]
		];
        return view('pnkpanel.home-page-banner.edit', compact('home_image'))->with($pageData);;
    }
	
    public function update(Request $request) {
		$actType = $request->actType;
		$image_id = $request->image_id;

        $data = $this->validate($request, [
            //'title' => 'required',
            // 'home_image' => 'required',
            // 'home_image_mobile' => 'required',
        ],
        [
            //'title.required' => 'Please enter title',
            // 'home_image.required' => 'Please uplode image',
            // 'home_image_mobile.required' => 'Please uplode image',
        ]);

        if ( $request->hasFile('home_image') || (!$request->hasFile('home_image') && $request->home_image_old != null) ) {
            if ($request->hasFile('home_image')!=null && $request->file('home_image')->isValid()) {
                $data = $this->validate($request, [
                    'home_image' => 'required',
                ]);
            }
        }
        
        if ($request->hasFile('home_image_mobile') || (!$request->hasFile('home_image_mobile_old') && $request->home_image_mobile_old != null)) {
            if ($request->hasFile('home_image_mobile')!=null && $request->file('home_image_mobile')->isValid()) {
                $data = $this->validate($request, [
                    'home_image_mobile' => 'required',
                ]);
            }
        }
        //echo $request->banner_position; exit;
		$home_image_data = HomeImage::findOrNew($image_id);
		$home_image_data->title 	=  $request->title;
		$home_image_data->image_alt_text 	=  $request->image_alt_text ? $request->image_alt_text : '';
		$home_image_data->link 	=  $request->link ? $request->link : '';
		$home_image_data->video_url 	=  $request->video_url ? $request->video_url : '';
		$home_image_data->video_url_mobile 	=  $request->video_url_mobile ? $request->video_url_mobile : '';
		$home_image_data->youtube_video 	=  $request->youtube_video;
		$home_image_data->banner_position 	=  ($request->has('banner_position')) ? $request->banner_position : 'HOME_MAIN';
		$home_image_data->display_position 	=  ($request->has('display_position')) ? $request->display_position : 'LEFT_TOP';
		$home_image_data->banner_text 	=  $request->banner_text ? $request->banner_text : '';
		//$home_image_data->position 	=  ($request->has('position')) ? $request->position : '';
		$home_image_data->position 	=  $request->position ? $request->position : '';
		$home_image_data->added_date 	=  date('Y-m-d H:i:s');
		$home_image_data->status 	=  $request->status;

		if($home_image_data->save()) {
			
			
			if ($request->hasFile('home_image')) {
				if ($request->file('home_image')->isValid()) {
					if(!file_exists(config('const.HOME_IMAGE_PATH'))) {
						File::makeDirectory(config('const.HOME_IMAGE_PATH'), $mode = 0777, true, true);
					}
					
					$image = $request->file('home_image');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = clearSpecialCharacters($original_filename)."_".$rand_num."_".$home_image_data->image_id.".".$image->getClientOriginalExtension();

					$image_name = str_replace(" ","_",$original_filename); //'banner_'.$home_image_data->image_id.'.'.$image->getClientOriginalExtension();
					
					$destination_path = config('const.HOME_IMAGE_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.$image_name;
					$image_resize = Image::make($orig_saved_file_path); 
					if($home_image_data->banner_position == 'HOME_MAIN')
					{
						$image_resize->resize(config('const.HOME_PAGE_DESKTOP_BANNER_WIDTH'), config('const.HOME_PAGE_DESKTOP_BANNER_HEIGHT'));
					}
					else if($home_image_data->banner_position == 'HOME_MIDDLE')
					{
						$image_resize->resize(config('const.HOME_PAGE_MIDDLE_DESKTOP_PROMOTION_WIDTH'), config('const.HOME_PAGE_MIDDLE_DESKTOP_PROMOTION_HEIGHT'));
					}
					else if($home_image_data->banner_position == 'HOME_MIDDLE_WHOLESALER')
					{
						$image_resize->resize(config('const.HOME_PAGE_MIDDLE_DESKTOP_WHOLESALER_WIDTH'), config('const.HOME_PAGE_MIDDLE_DESKTOP_WHOLESALER_HEIGHT'));
					}
					else if($home_image_data->banner_position == 'HOME_BOTTOM')
					{
						$image_resize->resize(config('const.HOME_PAGE_BOTTOM_DESKTOP_BEAUTY_WIDTH'), config('const.HOME_PAGE_BOTTOM_DESKTOP_BEAUTY_HEIGHT'));
					}
					else
					{
						$image_resize->resize(config('const.HOME_PAGE_DESKTOP_BANNER_WIDTH'), config('const.HOME_PAGE_DESKTOP_BANNER_HEIGHT'));
					}
					$image_resize->save($orig_saved_file_path);

					$home_image_data = HomeImage::find($home_image_data->image_id);
					$home_image_data->home_image = $image_name;
					$home_image_data->save();
				}
			}
			
			if ($request->hasFile('home_image_mobile')) {
				if ($request->file('home_image_mobile')->isValid()) {
					
					if(!file_exists(config('const.HOME_IMAGE_PATH'))) {
						File::makeDirectory(config('const.HOME_IMAGE_PATH'), $mode = 0777, true, true);
					}
					$image = $request->file('home_image_mobile');

					$rand_num = random_int(1000, 9999);
					$original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
					$original_filename = clearSpecialCharacters($original_filename)."_".$rand_num."_".$home_image_data->image_id.".".$image->getClientOriginalExtension();

					$image_name = str_replace(" ","_",$original_filename); //'mobile_banner_'.$home_image_data->image_id.'.'.$image->getClientOriginalExtension();
					$destination_path = config('const.HOME_IMAGE_PATH');
					$res = $image->move($destination_path, $image_name);
					
					$orig_saved_file_path = $destination_path.$image_name;
					$image_resize = Image::make($orig_saved_file_path); 
					
					if($home_image_data->banner_position == 'HOME_MAIN')
					{
						$image_resize->resize(config('const.HOME_PAGE_MOBILE_BANNER_WIDTH'), config('const.HOME_PAGE_MOBILE_BANNER_HEIGHT'));
					}
					else if($home_image_data->banner_position == 'HOME_MIDDLE')
					{
						$image_resize->resize(config('const.HOME_PAGE_MIDDLE_MOBILE_PROMOTION_WIDTH'), config('const.HOME_PAGE_MIDDLE_MOBILE_PROMOTION_HEIGHT'));
					}
					else if($home_image_data->banner_position == 'HOME_MIDDLE_WHOLESALER')
					{
						$image_resize->resize(config('const.HOME_PAGE_MIDDLE_MOBILE_WHOLESALER_WIDTH'), config('const.HOME_PAGE_MIDDLE_MOBILE_WHOLESALER_HEIGHT'));
					}
					else if($home_image_data->banner_position == 'HOME_BOTTOM')
					{
						$image_resize->resize(config('const.HOME_PAGE_BOTTOM_MOBILE_BEAUTY_WIDTH'), config('const.HOME_PAGE_BOTTOM_MOBILE_BEAUTY_HEIGHT'));
					}
					else
					{
						$image_resize->resize(config('const.HOME_PAGE_MOBILE_BANNER_WIDTH'), config('const.HOME_PAGE_MOBILE_BANNER_HEIGHT'));
					}
					
					//$image_resize->resize(config('const.HOME_PAGE_MOBILE_BANNER_WIDTH'), config('const.HOME_PAGE_MOBILE_BANNER_HEIGHT'));
					
					$image_resize->save($orig_saved_file_path);
					
					$home_image_data = HomeImage::find($home_image_data->image_id);
					$home_image_data->home_image_mobile = $image_name;
					$home_image_data->save();
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
		return redirect()->route('pnkpanel.home-page-banner.edit', $home_image_data->image_id);
	}
	
	public function delete($id) {
		$row = HomeImage::find($id);
		if($row) {
			$home_image = $row->home_image;
			$home_image_mobile = $row->home_image_mobile;
			if($home_image != null) {
				$destination_path = config('const.HOME_IMAGE_PATH');
				File::delete($destination_path.$home_image);
			}
			if($home_image_mobile != null) {
				$destination_path = config('const.HOME_IMAGE_PATH');
				File::delete($destination_path.$home_image_mobile);
			}
		}
		if(HomeImage::findOrFail($id)->delete()) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.home-page-banner.list');
	}

	public function deleteImage(Request $request) {
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;
		$actType = $request->actType;
		if(in_array($actType, ['delete_image']))
		{
			$destination_path = '';
			if($request->type == 'home_image') {
				$destination_path = config('const.HOME_IMAGE_PATH');
			} 
			if($request->type == 'home_image_mobile') {
				$destination_path = config('const.HOME_IMAGE_PATH');
			} 
			
			$image_name = $request->image_name;
			if(File::delete($destination_path.$image_name)) {
				
				if($request->type == 'home_image_mobile') {
					File::delete($destination_path.$image_name);
				} 
				$home_image_file = HomeImage::find($request->id);

				if($request->subtype == 'home_image') {
					$home_image_file->home_image = '';
				}
				if($request->subtype == 'home_image_mobile') {
					$home_image_file->home_image_mobile = '';
				}
				
				$home_image_file->save();
				
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
		
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}

    public function changeStatus(Request $request)
    {
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;

		$actType = $request->actType;
		if(in_array($actType, ['active', 'inactive']))
		{
			$id_str = $request->ids;
			if(empty($id_str))	
			{
				$success = false;
				$errors = ["message" => ["Please select record(s) to make it ".ucfirst($actType)."."]];
				$messages = [];
				$response_http_code = 400;
			} else {
				//$this->model()::whereIn("id",explode(",", $id_str))->update(['status' => ($actType == 'active' ? '1' : '0')]);
				$this->model()::whereKey(explode(",", $id_str))->update(['status' => ($actType == 'active' ? '1' : '0')]);
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_status")]];
				$response_http_code = 200;
			}
		}
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
	}

    public function bulkDelete(Request $request)
    {
        $success = false;
        $errors = [];
        $messages = [];
        $response_http_code = 400;

        $actType = $request->actType;
        if($actType == 'delete')
        {
            $id_str = $request->ids;
            if(empty($id_str))  
            {
                $success = false;
                $errors = ["message" => ["Please select record(s) to Delete."]];
                $messages = [];
                $response_http_code = 400;
            } else {
                $species_name_array = explode(",", $id_str);
                foreach($species_name_array as $key => $value) {
                    $row = HomeImage::select('home_image', 'image_id', 'home_image_mobile')->where('image_id', '=', $value)->first();
                    if($row && $row->count() > 0) {        
                        $image_id = $row->image_id;    
                        $image_name = $row->home_image;
                        $mobile_image_name = $row->home_image_mobile;
                        if(File::exists(config('const.HOME_IMAGE_PATH').$image_name) and !empty($image_name) )
                        {
                            File::delete(config('const.HOME_IMAGE_PATH').$image_name);
                        }
                        if(File::exists(config('const.HOME_IMAGE_PATH').$mobile_image_name) and !empty($mobile_image_name) )
                        {
                            File::delete(config('const.HOME_IMAGE_PATH').$mobile_image_name);
                        }
                        HomeImage::findOrFail($image_id)->delete();
                    }
                }
                $success = true;
                $errors = [];
                $messages = ["message" => [config("messages.msg_delete")]];
                $response_http_code = 200;
            }
        }
        return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages), $response_http_code);
    }

}
