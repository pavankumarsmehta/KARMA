<?php

namespace App\Http\Controllers\Pnkpanel\Traits;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;

trait CrudControllerTrait
{
	abstract function model();

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
				if(isset($request->is_custom_status) && $request->is_custom_status!="false"){
					$this->model()::whereKey(explode(",", $id_str))->update([$request->is_custom_status => ($actType == 'active' ? '1' : '0')]);
				}else{
					$this->model()::whereKey(explode(",", $id_str))->update(['status' => ($actType == 'active' ? '1' : '0')]);
				}
				//$this->model()::whereIn("id",explode(",", $id_str))->update(['status' => ($actType == 'active' ? '1' : '0')]);
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_status")]];
				$response_http_code = 200;
			}
		}
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages, "ids" => $id_str), $response_http_code);
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
				//$this->model()::whereIn("id",explode(",",$id_str))->delete();
				$this->model()::destroy(explode(",", $id_str));
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_delete")]];
				$response_http_code = 200;
			}
		}
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages, "ids" => $id_str), $response_http_code);
	}
    
	public function bulkUpdateRank(Request $request)
	{
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;

		$actType = $request->actType;
		
		
		if($actType == 'update_rank')
		{
			$ids_obj = $request->ids;
			if(empty($ids_obj))	
			{
				$success = false;
				$errors = ["message" => ["Please select record(s) to Update Rank."]];
				$messages = [];
				$response_http_code = 400;
			} else {
				//$this->model()::whereIn("id",explode(",", $id_str))->update(['status' => ($actType == 'active' ? '1' : '0')]);
				foreach($ids_obj as $record)
				{
					$idsArr[] = $record['id'];
					$this->model()::whereKey($record['id'])->update(['display_position' => $record['display_position']]);
				}
				$id_str = implode(',',$idsArr);
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_rank")]];
				$response_http_code = 200;
			}
		}
		
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages, "ids" => $id_str), $response_http_code);
	}

	public function bulkUpdateliftGateSettings(Request $request)
	{
		$success = false;
		$errors = [];
		$messages = [];
		$response_http_code = 400;

		$actType = $request->actType;
		
		
		if($actType == 'update_lift_gate_settings')
		{
			$ids_obj = $request->ids;
			if(empty($ids_obj))	
			{
				$success = false;
				$errors = ["message" => ["Please select record(s) to Update Rank."]];
				$messages = [];
				$response_http_code = 400;
			} else {
				//$this->model()::whereIn("id",explode(",", $id_str))->update(['status' => ($actType == 'active' ? '1' : '0')]);
				foreach($ids_obj as $record)
				{
					$idsArr[] = $record['id'];
					$this->model()::whereKey($record['id'])->update(['lift_gate_charge' => $record['lift_gate_charge'],'is_lift_gate' => $record['is_lift_gate']]);
				}
				$id_str = implode(',',$idsArr);
				$success = true;
				$errors = [];
				$messages = ["message" => [config("messages.msg_update_lift_gate_settings")]];
				$response_http_code = 200;
			}
		}
		
		return response()->json(array("success" => $success, "errors" => $errors, "messages" => $messages, "ids" => $id_str), $response_http_code);
	}
}