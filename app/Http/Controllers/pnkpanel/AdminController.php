<?php

namespace App\Http\Controllers\pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pnkpanel;
use Carbon\Carbon;
use DataTables;

class AdminController extends Controller
{
	use Traits\CrudControllerTrait;

    public function model()
    {
        return Pnkpanel::class;
    }
    
    public function list() {
		if(request()->ajax()) {
			$model = Pnkpanel::select([
				'admin_id',
				'email',
				'insert_datetime',
				'update_datetime',
				'admin_type',
				'status'
			]);
			
			$table = DataTables::eloquent($model);
			$table->addIndexColumn();
			$table->addColumn('checkbox', function($row) {
				return '<input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2 subChk" data-id="'.$row->admin_id.'" />';
			});
			$table->editColumn('email', function($row) {
				return "<a href=".route('pnkpanel.admin.edit', $row->admin_id).">".$row->email."</a>";
			});
			$table->editColumn('insert_datetime', function($row) {
				return Carbon::parse($row->insert_datetime)->format('m/d/Y');
			});
			$table->editColumn('update_datetime', function($row) {
				return Carbon::parse($row->update_datetime)->format('m/d/Y');
			});
			$table->editColumn('status', function($row) {
				return ($row->status ? 'Active':'Inactive');
			});
			$table->addColumn('action', function($row) {
				return (string)view('pnkpanel.component.datatable_action', ['id' => $row->admin_id]);
			});
			$table->rawColumns(['checkbox', 'email', 'action']);
			return $table->make(true);
		}
		
		$pageData['page_title'] = "Admin List";
		$pageData['meta_title'] = "Admin List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Admin List',
				 'url' =>route('pnkpanel.admin.list')
			 ]
		];
		
		return view('pnkpanel.admin.list')->with($pageData);
	}
	
	public function edit($admin_id = 0) {
        if($admin_id > 0) {
			$admin = Pnkpanel::findOrFail($admin_id);
		} else {
			$admin =  new Pnkpanel;
		}
		
		$prefix = ($admin_id > 0 ? 'Edit' : 'Add New');
		$pageData['page_title'] = $prefix.' Admin';
		$pageData['meta_title'] = $prefix.' Admin';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Admin List',
				 'url' =>route('pnkpanel.admin.list')
			 ],
			 [
				 'title' => $prefix.' Admin',
				 'url' =>route('pnkpanel.admin.edit', $admin_id)
			 ]
		];
		
        return view('pnkpanel.admin.edit', compact('admin'))->with($pageData);
    }
	
    public function update(Request $request) {
		$actType = $request->actType;
		$admin_id = $request->admin_id;

		$admin = Pnkpanel::findOrNew($admin_id);
		
		$this->validate($request, [
			'email'	 			=> 'required|email|unique:hba_admin,email,'.$admin->admin_id.',admin_id',
			'admin_type'	=> 'required|string',
			'status'				=> 'required|numeric'
		]);
		
		if($actType == 'add' || $request->password != '')
		{
			$this->validate($request, [
				'password' => 'required|confirmed|min:6'
			]);
		}
		
		$admin->email 	=  $request->email;
		if($request->password != '') {
			$admin->password =  Hash::make($request->password);
		}
		$admin->admin_type =  $request->admin_type;
		$admin->rights = isset($request->rights) ? implode(',', $request->rights) : '';
		$admin->status 	=  $request->status;
		
		if($admin->save()) {
			if($actType == 'add') {
				session()->flash('site_common_msg', config('messages.msg_add'));
			} else {
				session()->flash('site_common_msg', config('messages.msg_update')); 
			}
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_add_err'));
		}
		return redirect()->route('pnkpanel.admin.edit', $admin->admin_id);
	}
	
	public function delete($admin_id){
		if(Pnkpanel::findOrFail($admin_id)->delete()) {
			session()->flash('site_common_msg', config('messages.msg_delete')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_delete_err')); 
		}
		return redirect()->route('pnkpanel.admin.list');
	}
	
}
