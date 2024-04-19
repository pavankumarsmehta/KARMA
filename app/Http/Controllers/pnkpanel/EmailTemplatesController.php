<?php

namespace App\Http\Controllers\Pnkpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplates;
use App\Http\Controllers\pnkpanel\Traits\CrudControllerTrait;
use DataTables;

class EmailTemplatesController extends Controller
{
	use CrudControllerTrait;

    public function model()
    {
        return EmailTemplates::class;
    }

    public function getEmailContent($email_templates_id = 0)
	{
		$mail_body_res = EmailTemplates::select('subject','mail_body')->where('email_templates_id', $email_templates_id)->first();
		if($mail_body_res && $mail_body_res->count() > 0) {
			$EmailBody = str_replace('{$Site_URL}',config('const.SITE_IMAGES_URL'),$mail_body_res->mail_body);
		    $response['status'] = 200;
		    $response['message'] = "Success";
		    $response['title'] = $mail_body_res->subject;
		    $response['body'] = trim(preg_replace('/\s+/', ' ', stripcslashes($EmailBody)));
		    return response()->json($response, 200);
		} else {
	        $response['status'] = 404;
	        $response['message'] = "Data not found. Please try again later.";
	        return response()->json($response, 400);
		}
	}

    public function list() {
		if(request()->ajax()) {
			$model = EmailTemplates::select([
				'email_templates_id',
				'title',
				'subject',
				'status'
			]);
			$table = DataTables::eloquent($model);

			$table->addColumn('action', function($row) {
				return '<a href="javascript:void(0);" data-toggle="tooltip" data-id="'.$row->email_templates_id.'" data-original-title="Edit" title="Edit" class="edit btn btn-sm btn-primary btnEditRecord"><i class="bx bx-edit"></i></a><a href="'.route('pnkpanel.email-templates.get_content', $row->email_templates_id).'"  title="View" class="simple-ajax-modal btn btn-sm btn-primary btnViewModal"><i class="fas fa-eye"></i></a>';
			});

			$table->rawColumns(['action']);
			return $table->make(true);
		}
		
		$pageData['page_title'] = "Email Templates List";
		$pageData['meta_title'] = "Email Templates List";
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Email Templates List',
				 'url' =>route('pnkpanel.email-templates.list')
			 ]
		];
		
		return view('pnkpanel.email_templates.list')->with($pageData);
	}
	
	public function edit($id = 0) {

        if($id > 0) {
			$emailtemplate = EmailTemplates::findOrFail($id);
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_update_err'));
		}

		$pageData['page_title'] = 'Edit Email Template';
		$pageData['meta_title'] = 'Edit Email Template';
		$pageData['breadcrumbs'] = [
			 [
				 'title' => 'Email Templates List',
				 'url' =>route('pnkpanel.email-templates.list')
			 ],
			 [
				 'title' => 'Edit Email Template',
				 'url' =>route('pnkpanel.email-templates.edit', $id)
			 ]
		];
		
        return view('pnkpanel.email_templates.edit', compact('emailtemplate'))->with($pageData);;
    }
	
    public function update(Request $request) {
		$actType = $request->actType;
		$id = $request->id;
		$emailtemplate = EmailTemplates::findOrNew($id);
		
		$this->validate($request, [
			'subject'	 	=> 'required',
			'mail_body'		=> 'required',
		],
		[
        	'subject.required' => 'Please enter email subject',
        	'mail_body.required' => 'Please enter email content',
		]);

		$emailtemplate->subject = isset($request->subject) ? str_replace(array('\r', '\n'), '', $request->subject) : '';
		$emailtemplate->mail_body = isset($request->mail_body) ? str_replace(array('\r', '\n'), '', $request->mail_body) : '';
		
		if($emailtemplate->save()) {
			session()->flash('site_common_msg', config('messages.msg_update')); 
		} else {
			session()->flash('site_common_msg_err', config('messages.msg_add_err'));
		}
		return redirect()->route('pnkpanel.email-templates.edit', $emailtemplate->email_templates_id);
	}
}