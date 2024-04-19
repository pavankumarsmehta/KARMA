<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Frontmenu;
use App\Models\ProductsBrand;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use File;

class MenuListController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return Frontmenu::class;
    }
    
    public function menulist(Request $request) {
		
		$mainmenu = Frontmenu::all()->where("status","=","1")->where("parent_id","=","0");
		
        $pageData['page_title'] = "Frontmenu Page";
        $pageData['meta_title'] = "Frontmenu Page";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Frontmenu Page',
                'url' => route('pnkpanel.frontmenu.menulist')
            ]
        ];
        return view('pnkpanel.frontmenu.menulist',compact('mainmenu'))->with($pageData);
	 }

	 public static function getCategoryTreeGridData($records = null, $level = 0, $columnName, $columnSortOrder) {
		$data_arr = array();
		if($columnSortOrder == 'asc') {
			$records = $records->sortBy($columnName);
		} else {
			$records = $records->sortByDesc($columnName);
		}
		foreach($records as $record){
			if($record->parent_id == 0) {
				$level = 0;
			}
			$levelString = str_repeat("&emsp;&ensp;", $level);
			$checkbox = '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$record->menu_id.'" />';
			$menu_id = $record->menu_id;
			$parent_id = $record->parent_id;
			$menu_title = $levelString . ' <a href="' . route("pnkpanel.frontmenu.edit", $record->menu_id) . '">'  . ($record->parent_id == '0' ? '<strong>' : '') . '['  .( $level+1 ) . '] ' .  $record->menu_title . ($record->parent_id == '0' ? '</strong>' : '') . '</a>';
		
			
			$status = ($record->status == '1' ? 'Active' : 'Inactive');
			$action = (string)view('pnkpanel.component.datatable_action', ['id' => $record->menu_id]);

			$data_arr[] = array(
				"checkbox" => $checkbox,
				"menu_id" => $menu_id,
				"parent_id" => $parent_id,
				"menu_title" => $menu_title,
				"status" => $status,
				"action" => $action
			);
			
			if($record->childrenRecursive && count($record->childrenRecursive)>0) {
				$data_arr = array_merge($data_arr, self::getCategoryTreeGridData($record->childrenRecursive, $level+1, $columnName, $columnSortOrder)); ;
			}
		}
		return $data_arr;
	}

	
	public function edit($id = 0,$parent_id='') {

       
		if($parent_id=='subcatadd')
		{   
			$frontmenu = new Frontmenu;
			$parent_id=$id;
			$create_subcat='Yes';
		}
		else
		{
			if($id > 0) {
			   $frontmenu = Frontmenu::findOrFail($id);
			} else {
				$frontmenu =  new Frontmenu;
			}

			$parent_id=0;
			$create_subcat='No';
		}
		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Frontmenu';
		$pageData['meta_title'] = $prefix.' Frontmenu';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Frontmenu List',
				 'url' =>route('pnkpanel.frontmenu.menulist')
			 ],
			 [
				 'title' => $prefix.' Frontmenu',
				 'url' =>route('pnkpanel.frontmenu.menuedit', $id)
			 ]
		];
		
        return view('pnkpanel.frontmenu.menuedit', compact('frontmenu','parent_id','create_subcat'))->with($pageData);
    }
  
	
	
    public function update(Request $request) {
		
		//dd($request);
		$actType = $request->actType;
		$menu_id = $request->menu_id;
		$is_delete = $request->is_delete;
		$del_chk = $request->del_chk;
		$del_chk_arr = explode(",",$del_chk);
		
		/*if($actType == 'add') {
			$check_duplicate = Frontmenu::where('menu_title', $request->menu_title)->first();
		} else {
			$check_duplicate = Frontmenu::where('menu_title', $request->menu_title)->where('menu_id', '<>', $menu_id)->first();
		}
		if($check_duplicate)
		{
			session()->flash('site_common_msg_err', 'Menu name already exists. Please change it');
			return redirect()->route('pnkpanel.frontmenu.menuedit', $menu_id);
		}*/

		$this->validate($request, [
			'menu_title'	=> 'required|string'
		]);
		
		if($request->rank) {
			$this->validate($request, [
				'rank'	=> 'numeric'
			]);
		}
		if ($request->hasFile('menu_image')) {
			if ($request->file('menu_image')->isValid()) {
				$data = $this->validate($request, [
					'menu_image' => 'required|mimes:jpeg,png,webp,jpg',
				]);
			}
		}
		if ($request->hasFile('menu_image1')) {
			if ($request->file('menu_image1')->isValid()) {
				$data = $this->validate($request, [
					'menu_image1' => 'required|mimes:jpeg,png,webp,jpg',
				]);
			}
		}
		if ($request->hasFile('menu_image2')) {
			if ($request->file('menu_image2')->isValid()) {
				$data = $this->validate($request, [
					'menu_image2' => 'required|mimes:jpeg,png,webp,jpg',
				]);
			}
		}
				
		$frontmenu = Frontmenu::findOrNew($menu_id);
		$frontmenu->parent_id =  $request->parent_id;
		$frontmenu->menu_title =  $request->menu_title;
		$frontmenu->menu_link 	=  $request->menu_link;
		$frontmenu->rank 	=  (empty($request->rank) AND trim($request->rank) == '') ?  999999 : $request->rank;
		if((isset($request->category_id) && !empty($request->category_id))){
			$frontmenu->category_id 	=  (isset($request->category_id) && !empty($request->category_id)) ? $request->category_id : ''; 
		}else{
			$frontmenu->category_id  = '';
		}
		if((isset($request->brand_id) && !empty($request->brand_id))){
			$frontmenu->brand_id 	=  (isset($request->brand_id) && !empty($request->brand_id)) ? $request->brand_id : ''; 
		}else{
			$frontmenu->brand_id 	= '';
		}		
		
		$frontmenu->status 	=  $request->status;
		$frontmenu->is_label 	=  $request->is_label;
		$frontmenu->is_banner 	=  $request->is_banner;
		
		$frontmenu->menu_label 	=  $request->menu_label;
		$frontmenu->menu_label1 =  $request->menu_label1;
		$frontmenu->menu_label2 =  $request->menu_label2;

		$frontmenu->menu_custom_link 	=  $request->menu_custom_link;
		$frontmenu->menu_custom_link1 =  $request->menu_custom_link1;
		$frontmenu->menu_custom_link2 =  $request->menu_custom_link2;

	     if($frontmenu->save()) {
			
			
			if ($request->hasFile('menu_image')) {
				if ($request->file('menu_image')->isValid()) {
					
					if(!file_exists(config('const.MENUIMAGE_PATH'))) {
						File::makeDirectory(config('const.MENUIMAGE_PATH'), $mode = 0777, true, true);
					}
					
					//Main image
					$image = $request->file('menu_image');
					
					$rand_num = random_int(1000, 9999);
					$original_filename = $frontmenu->menu_image;
				   
					$original_filename = 'MENU_IMG'."_".$rand_num.".".$image->getClientOriginalExtension();
					
				    $menu_image = $original_filename; 
					//echo $image_name; exit;
					$destination_path = config('const.MENUIMAGE_PATH');
					
					$res = $image->move($destination_path, $menu_image);
					
					if($res) {
						$orig_saved_file_path = $destination_path.'/'.$menu_image;

						# Resize Large Image
						if(!File::exists(config('const.MENUIMAGE_PATH'))) {
							File::makeDirectory(config('const.MENUIMAGE_PATH'), $mode = 0777, true, true);
						}
						$image_resize = Image::make($orig_saved_file_path);  
						$image_resize->resize(config('const.MENUIMAGE_WIDTH'), config('const.MENUIMAGE_HEIGHT'));
						$image_resize->save(config('const.MENUIMAGE_PATH').$menu_image);
						
											
					}
					$frontmenu = Frontmenu::find($frontmenu->menu_id);
					$frontmenu->menu_image = $menu_image;
					$frontmenu->save();
				}
			}
			if ($request->hasFile('menu_image1')) {
				if ($request->file('menu_image1')->isValid()) {
					
					if(!file_exists(config('const.MENUIMAGE_PATH'))) {
						File::makeDirectory(config('const.MENUIMAGE_PATH'), $mode = 0777, true, true);
					}
					
					//Main image
					$image = $request->file('menu_image1');
					
					$rand_num = random_int(1000, 9999);
					$original_filename = $frontmenu->menu_image1;
				   
					$original_filename = 'MENU_IMG1'."_".$rand_num.".".$image->getClientOriginalExtension();
					
				    $menu_image1 = $original_filename; 
					//echo $image_name; exit;
					$destination_path = config('const.MENUIMAGE_PATH');
					
					$res = $image->move($destination_path, $menu_image1);
					
					if($res) {
						$orig_saved_file_path = $destination_path.'/'.$menu_image1;

						# Resize Large Image
						if(!File::exists(config('const.MENUIMAGE_PATH'))) {
							File::makeDirectory(config('const.MENUIMAGE_PATH'), $mode = 0777, true, true);
						}
						$image_resize = Image::make($orig_saved_file_path);  
						$image_resize->resize(config('const.MENUIMAGE_WIDTH'), config('const.MENUIMAGE_HEIGHT'));
						$image_resize->save(config('const.MENUIMAGE_PATH').$menu_image1);
						
											
					}
					$frontmenu = Frontmenu::find($frontmenu->menu_id);
					$frontmenu->menu_image1 = $menu_image1;
					$frontmenu->save();
				}
			}
			if ($request->hasFile('menu_image2')) {
				if ($request->file('menu_image2')->isValid()) {
					
					if(!file_exists(config('const.MENUIMAGE_PATH'))) {
						File::makeDirectory(config('const.MENUIMAGE_PATH'), $mode = 0777, true, true);
					}
					
					//Main image
					$image = $request->file('menu_image2');
					
					$rand_num = random_int(1000, 9999);
					$original_filename = $frontmenu->menu_image2;
				   
					$original_filename = 'MENU_IMG2'."_".$rand_num.".".$image->getClientOriginalExtension();
					
				    $menu_image2 = $original_filename; 
					//echo $image_name; exit;
					$destination_path = config('const.MENUIMAGE_PATH');
					
					$res = $image->move($destination_path, $menu_image2);
					
					if($res) {
						$orig_saved_file_path = $destination_path.'/'.$menu_image2;

						# Resize Large Image
						if(!File::exists(config('const.MENUIMAGE_PATH'))) {
							File::makeDirectory(config('const.MENUIMAGE_PATH'), $mode = 0777, true, true);
						}
						$image_resize = Image::make($orig_saved_file_path);  
						$image_resize->resize(config('const.MENUIMAGE_WIDTH'), config('const.MENUIMAGE_HEIGHT'));
						$image_resize->save(config('const.MENUIMAGE_PATH').$menu_image2);
						
											
					}
					$frontmenu = Frontmenu::find($frontmenu->menu_id);
					$frontmenu->menu_image2 = $menu_image2;
					$frontmenu->save();
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
		return redirect()->route('pnkpanel.frontmenu.menuedit', $frontmenu->menu_id);
	}
	
	public function delete($id) {
		if($this->deleteFrontmenu($id)) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.frontmenu.menulist');
	}
	
	private function deleteFrontmenu($id) {
		Frontmenu::where('menu_id', $id)->delete();
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
			if($request->type == 'menu_image') {
				$destination_path = config('const.MENUIMAGE_PATH');
			} 
			if($request->type == 'menu_image1') {
				$destination_path = config('const.MENUIMAGE_PATH');
			} 
			if($request->type == 'menu_image2') {
				$destination_path = config('const.MENUIMAGE_PATH');
			} 
			//echo config('const.MANUFACTURER_IMAGE_PATH').$request->image_name; exit;
			$image_name = $request->image_name;
			//echo $destination_path.$request->image_name; exit;
			
			//echo $request->subtype; exit;
			if(File::delete($destination_path.$image_name)) {
				
				$frontmenu = Frontmenu::find($request->id);

				if($request->subtype == 'menu_image') {
					$frontmenu->menu_image = NULL;
				}
				if($request->subtype == 'menu_image1') {
					$frontmenu->menu_image1 = NULL;
				}
				if($request->subtype == 'menu_image2') {
					$frontmenu->menu_image2 = NULL;
				}
				
				$frontmenu->save();
				
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
		public static function drawCategoryTreeDropdown($records = null, $level = 0, $defaultSelectedCategoryId = '') {
		$html = array();
		foreach($records as $record){
			if($record->parent_id == 0) {
				$level = 0;
			}
			$levelString = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level).($level?"|".$level."|&nbsp;&nbsp;":"&raquo;&nbsp;");
			
			$html[] = "<option value=\"" . $record->menu_id."\" " . ($record->menu_id == $defaultSelectedCategoryId ? " selected" : "") . ">". $levelString . $record->menu_title . "</option>";
			
			if($record->childrenRecursive && count($record->childrenRecursive)>0) {
				$html = array_merge($html, self::drawCategoryTreeDropdown($record->childrenRecursive, $level+1, $defaultSelectedCategoryId)); ;
			}
		}
		return $html;
	}
	
}
