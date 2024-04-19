<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticPages;
use App\Models\NewsPress;
use Carbon\Carbon;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use File;
use DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;

class NewsPressController extends Controller
{
    use CrudControllerTrait;

    public function model()
    {
        $action_method = explode('/', url()->full());
        if (in_array("manage-news-press", $action_method)) {
            return NewsPress::class;
        }
    }

    public function manage_news_press_list()
    {
        if (request()->ajax()) {
            $model = NewsPress::select('*');
            $table = DataTables::eloquent($model);
            $table->addIndexColumn();
            $table->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="' . $row->newspress_id . '" />';
            });
            $table->editColumn('status', function ($row) {
                return ($row->status ? 'Active' : 'Inactive');
            });
            $table->editColumn('action', function ($row) {
                $action = (string)view('pnkpanel.component.datatable_action', ['id' => $row->newspress_id]);
                return $action;
            });
            $table->editColumn('type', function ($row) {
                if($row->type == '1'){
                    return "News";
                }else{
                    return "Press";
                }
            });
            $table->rawColumns(['checkbox', 'action']);
            return $table->make(true);
        }

        $pageData['page_title'] = "Manage News Press";
        $pageData['meta_title'] = "Manage News Press";
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Manage News Press',
                'url' => route('pnkpanel.manage-news-press.list')
            ]
        ];
        return view('pnkpanel.newspress.newspress_list')->with($pageData);
    }

    public function manage_news_press_edit($id = 0)
    {
        if ($id > 0) {
            $newspress = NewsPress::findOrFail($id);
        } else {
            $newspress =  new NewsPress;
        }
        $prefix = ($id > 0 ? 'Edit' : 'Add New');
        $pageData['page_title'] = $prefix . ' News Press';
        $pageData['meta_title'] = $prefix . ' News Press';
        $pageData['breadcrumbs'] = [
            [
                'title' => 'Manage News Press',
                'url' => route('pnkpanel.manage-news-press.list')
            ],
            [
                'title' => $prefix . ' News Press',
                'url' => route('pnkpanel.manage-news-press.edit', $id)
            ]
        ];
        return view('pnkpanel.newspress.newspress_edit', compact('newspress'))->with($pageData);;
    }

    public function manage_news_press_update(Request $request)
    {   
        $actType = $request->actType;
        $id = $request->id;
        $is_delete = $request->is_delete;
        $del_chk = $request->del_chk;
        $del_chk_arr = explode(",",$del_chk);
        $newspress = NewsPress::findOrNew($id);
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required',
            'date' => 'required',
            'type' => 'required',
        ],
        [
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'date.required' => 'Date is required',
            'type.required' => 'Type is required',
        ]);

        $newspress->title     =  $request->title;
        $newspress->description =  $request->description;
        $newspress->date     =  $request->date ? $request->date :'';
        $newspress->type     =  $request->type;
        $newspress->last_updated     =  date("Y-m-d H:i:s", time());
        $newspress->status     =  $request->status;


        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                
                if(!file_exists(config('const.NEWSPRESS_IMG_PATH'))) {
                    File::makeDirectory(config('const.NEWSPRESS_IMG_PATH'), $mode = 0777, true, true);
                }
                
                $image = $request->file('image');

                $rand_num = random_int(1000, 9999);
                $original_filename = str_replace(".".$image->getClientOriginalExtension(),"",$image->getClientOriginalName());
                $original_filename = strtolower(clearSpecialCharacters($original_filename))."_".$rand_num.".".$image->getClientOriginalExtension();

                $image_name = $original_filename;
                $destination_path = config('const.NEWSPRESS_IMG_PATH');
                $res = $image->move($destination_path, $image_name);
                
                $orig_saved_file_path = $destination_path.'/'.$image_name;					

                $newspress->image = $image_name;
                $newspress->save();
            }
        }

        if ($newspress->save()) {
            if ($actType == 'add') {
                session()->flash('site_common_msg', config('messages.msg_add'));
                return redirect()->route('pnkpanel.manage-news-press.list', $newspress->newspress_id);
            } else {
                session()->flash('site_common_msg', config('messages.msg_update'));
            }
        } else {
            session()->flash('site_common_msg_err', config('messages.msg_add_err'));
        }
        return redirect()->route('pnkpanel.manage-news-press.list', $newspress->newspress_id);
    }

    public function manage_news_press_delete($id)
    {
        if (NewsPress::findOrFail($id)->delete()) {
            session()->flash('site_common_msg', config('messages.msg_delete'));
        } else {
            session()->flash('site_common_msg_err', config('messages.msg_delete_err'));
        }
        return redirect()->route('pnkpanel.manage-news-press.list');
    }

    public function deleteImage(Request $request)
	{
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;

		$actType = $request->actType;
		if (in_array($actType, ['delete_image'])) {
			if ($request->type == 'image' ||  $request->type == 'image') {
				$image_name = $request->image;
				File::delete(config('const.NEWSPRESS_IMG_PATH') . $image_name);
				$newspress = NewsPress::find($request->id);
                $newspress->image = NULL;
				$newspress->save();

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
}
