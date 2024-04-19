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

class FrontmenuController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return Frontmenu::class;
    }
    
    public function list(Request $request) {
		
		/*if (request()->ajax()) {

            $model = Frontmenu::select('*');
            $table = DataTables::eloquent($model);
            $table->addIndexColumn();
            $table->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->menu_id . '" />';
            });
            $table->editColumn('menu_title', function ($row) {
               	return "<a href=" . URL('/') . '/pnkpanel/frontmenu/edit/' . $row->menu_id."". ">" . $row->menu_title ."". "</a>";
				
            });
            $table->editColumn('status', function ($row) {
                return ($row->status ? 'Active' : 'Inactive');
            });
             $table->editColumn('rank', function ($row) {
               	return $row->rank ;
				
            });
            $table->editColumn('action', function ($row) {
                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->menu_id]);
                return $action;
            });

            $table->rawColumns(['checkbox', 'menu_title','rank','action']);
            return $table->make(true);
        }*/
       
       if(request()->ajax()) {
			$draw = $request->get('draw');
			$start = $request->get("start");
			$rowperpage = $request->get("length");

			$columnIndex_arr = $request->get('order');
			$columnName_arr = $request->get('columns');
			$order_arr = $request->get('order');
			$search_arr = $request->get('search');
			
			$columnName = 'menu_id';
			if(isset($columnIndex_arr)) {
				$columnIndex = $columnIndex_arr[0]['column'];
				$columnName = $columnName_arr[$columnIndex]['data'];
			}
			
			$columnSortOrder = $order_arr[0]['dir'] ?? 'asc';
			$searchValue = $search_arr['value'] ?? $columnName_arr[1]['search']['value'];

			$where = [];
			if(isset($searchValue) && $searchValue != '') {
				$where[] = ['category_name', 'like', '%' . $searchValue . '%'];
			}
			$totalRecords = Frontmenu::select('count(*) as allcount')->count();
			$totalRecordswithFilter = Frontmenu::select('count(*) as allcount')
				->orWhere($where)
				->count();
				
			$data_arr = array();
			if(isset($searchValue) && $searchValue != '') {
				$records = Frontmenu::where($where)->with('childrenRecursive')->get();
			} else {
				$records = Frontmenu::where('parent_id', '=', '0')->with('childrenRecursive')->get();
			}
			$data_arr = self::getCategoryTreeGridData($records, 0, $columnName, $columnSortOrder);


			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $totalRecords,
				"iTotalDisplayRecords" => $totalRecordswithFilter,
				"aaData" => $data_arr
			);

			echo json_encode($response);
			exit;
		}
        $pageData['page_title'] = "Frontmenu Page";
        $pageData['meta_title'] = "Frontmenu Page";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Frontmenu Page',
                'url' => route('pnkpanel.frontmenu.list')
            ]
        ];
        return view('pnkpanel.frontmenu.list')->with($pageData);
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
			$menu_title = $levelString . ' <a href="' . route("pnkpanel.frontmenu.edit", $record->menu_id) . '">'  . ($record->parent_id == '0' ? '<strong>' : '') . $record->menu_title . ($record->parent_id == '0' ? '</strong>' : '') . '</a>';
		
			
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

	
	public function edit($id = 0) {
        if($id > 0) {
			$frontmenu = Frontmenu::findOrFail($id);
		} else {
			$frontmenu =  new Frontmenu;
		}
		
		$prefix = ($id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Frontmenu';
		$pageData['meta_title'] = $prefix.' Frontmenu';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Frontmenu List',
				 'url' =>route('pnkpanel.frontmenu.list')
			 ],
			 [
				 'title' => $prefix.' Frontmenu',
				 'url' =>route('pnkpanel.frontmenu.edit', $id)
			 ]
		];
		
        return view('pnkpanel.frontmenu.edit', compact('frontmenu'))->with($pageData);;
    }
	
    public function update(Request $request) {
		
		//dd($request);
		$actType = $request->actType;
		$menu_id = $request->menu_id;
		$is_delete = $request->is_delete;
		$del_chk = $request->del_chk;
		$del_chk_arr = explode(",",$del_chk);
		
		if($actType == 'add') {
			$check_duplicate = Frontmenu::where('menu_title', $request->menu_title)->first();
		} else {
			$check_duplicate = Frontmenu::where('menu_title', $request->menu_title)->where('menu_id', '<>', $menu_id)->first();
		}
		if($check_duplicate)
		{
			session()->flash('site_common_msg_err', 'Menu name already exists. Please change it');
			return redirect()->route('pnkpanel.frontmenu.edit', $menu_id);
		}

		$this->validate($request, [
			'menu_title'	=> 'required|string'
		]);
		
		if($request->rank) {
			$this->validate($request, [
				'rank'	=> 'numeric'
			]);
		}
		
		
				
		$frontmenu = Frontmenu::findOrNew($menu_id);
		$frontmenu->parent_id 	=  $request->parent_id;
		$frontmenu->menu_title =  $request->menu_title;
		$frontmenu->menu_link 	=  $request->menu_link;
		$frontmenu->rank 	=  (empty($request->rank) AND trim($request->rank) == '') ?  999999 : $request->rank;		
		$frontmenu->status 	=  $request->status;
		
	     if($frontmenu->save()) {
			
		   if($actType == 'add') {
				session()->flash('site_common_msg', config('messages.msg_add'));
			} else {
				session()->flash('site_common_msg', config('messages.msg_update')); 
			}
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_add_err'));
		}
		return redirect()->route('pnkpanel.frontmenu.edit', $frontmenu->menu_id);
	}
	
	public function delete($id) {
		if($this->deleteFrontmenu($id)) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.frontmenu.list');
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
			if($request->type == 'manufacturer_logo_image') {
				$destination_path = config('const.MANUFACTURER_IMAGE_PATH');
			} 
			if($request->type == 'manufacturer_page_header_image') {
				$destination_path = config('const.MANUFACTURER_IMAGE_PATH');
			} 

			if($request->type == 'manufacturer_page_mobile_header_image') {
				$destination_path = config('const.MANUFACTURER_IMAGE_PATH');
			} 
			//echo config('const.MANUFACTURER_IMAGE_PATH').$request->image_name; exit;
			$image_name = $request->image_name;
			//echo $destination_path.$request->image_name; exit;
			
			//echo $request->subtype; exit;
			if(File::delete($destination_path.$image_name)) {
				
				$manufacturer = Manufacturer::find($request->id);

				if($request->subtype == 'manufacturer_logo_image') {
					$manufacturer->manufacturer_logo_image = NULL;
				}
				if($request->subtype == 'manufacturer_page_header_image') {
					$manufacturer->manufacturer_page_header_image = NULL;
				}
				if($request->subtype == 'manufacturer_page_mobile_header_image') {
					$manufacturer->manufacturer_page_mobile_header_image = NULL;
				}
				$manufacturer->save();
				
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
