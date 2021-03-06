<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FormatConverter;
use App\PrivateDetail;
use App\PrivateModel;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PrivateController extends Controller 
{
	public function activedPrivate($uniqueNumber, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		if ($user->unique_number != $uniqueNumber) {
			return response()->json([
				'status' => 404,
				'message' => 'User is not found',
			], 404);
		}
		
		$user = User::whereUniqueNumber($uniqueNumber)
			->roleApps()
			->appsActived()
			->first();
		if (!$user) {
			return response()->json([
				'status' => 404,
				'message' => 'User is not found',
			], 404);
		}
		
		$model = null;
		if ($user->role == User::ROLE_STUDENT) {
		    $model = PrivateModel::with(['student', 'teacher'])->whereUserId($user->id)->whereStatus(PrivateModel::STATUS_ON_GOING)->orderBy('private.created_at', 'desc')->get();
		} else if ($user->role == User::ROLE_TEACHER) {
		    $model = PrivateModel::with(['student', 'teacher'])->whereTeacherId($user->id)->whereStatus(PrivateModel::STATUS_ON_GOING)->orderBy('private.created_at', 'desc')->get();
		}
		
		if (!$model) {
			return response()->json([
				'status' => 404,
				'message' => 'Private Status not `On Going`',
			], 404);
		}
		
		if (count($model) <= 0) {
			$model = null;
		}
		
		return response()->json([
			'status' => 200,
			'message' => 'success',
			'data' => $model,
		], 200);
	}
	
	public function activedPrivates($uniqueNumber, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		if ($user->unique_number != $uniqueNumber || $user->role != User::ROLE_TEACHER) {
			return response()->json([
				'status' => 404,
				'message' => 'User is not found',
			], 404);
		}
		
		$user = User::whereUniqueNumber($uniqueNumber)
			->roleApps()
			->appsActived()
			->first();
		if (!$user) {
			return response()->json([
				'status' => 404,
				'message' => 'User is not found',
			], 404);
		}
		
		$model = PrivateModel::with(['student', 'teacher'])->whereTeacherId($user->id)->whereStatus(PrivateModel::STATUS_ON_GOING)->orderBy('private.created_at', 'desc')->get();
		if (!$model) {
			return response()->json([
				'status' => 404,
				'message' => 'Private Status not `On Going`',
			], 404);
		}
		
		return response()->json([
			'status' => 200,
			'message' => 'success',
			'data' => $model,
		], 200);
	}
	
	public function check($uniqueNumber, $privateId, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		if ($user->unique_number != $uniqueNumber) {
			return response()->json([
				'status' => 404,
				'message' => 'User is not found',
			], 404);
		}
		
		$user = User::whereUniqueNumber($uniqueNumber)
			->roleApps()
			->appsActived()
			->first();
		if (!$user) {
			return response()->json([
				'status' => 404,
				'message' => 'User is not found',
			], 404);
		}
		
		$validators = \Validator::make($request->all(), [
			'private_detail_id' => 'required|exists:private_detail,id',
			'on_at' => 'required',
			'checklist' => 'required',
			'description' => 'required',
		]);
		
		if ($validators->fails()) {
			return response()->json([
				'status' => 400,
				'message' => 'Some parameters is invalid',
				'validators' => FormatConverter::parseValidatorErrors($validators),
			], 400);
		}
		
		$private = PrivateModel::whereId($privateId)->whereStatus(PrivateModel::STATUS_ON_GOING)->first();
		if (!$private) {
			return response()->json([
				'status' => 404,
				'message' => 'Private Status not `On Going`',
			], 404);
		}
		
		$privateDetail = PrivateDetail::whereId($request->private_detail_id)
				->first();
		if (!$privateDetail) {
			return response()->json([
				'status' => 404,
				'message' => 'Private is not found',
			], 404);
		}
		
		$today = strtotime(date('Y-m-d'));
		$date = strtotime(Carbon::parse($request->on_at)->toDateString());
		if($today < $date) {
			return response()->json([
				'status' => 404,
				'message' => 'Hari tersebut belum bisa di ceklis',
			], 404);
		}
		
		$details = null;
		if ($user->role == User::ROLE_TEACHER) {
			$details = $privateDetail->teacher_details;
		} else if ($user->role == User::ROLE_STUDENT) {
			$details = $privateDetail->student_details;
		}
		
		$onAts = json_decode($details, true);
		$detail = [];
		foreach ($onAts as $onAt) {
			if ($onAt['on_at'] == $request->on_at) {
				$onAt['check'] = $request->checklist;
				$onAt['check_at'] = Carbon::now()->toDateTimeString();
				$onAt['description'] = $request->description;
			}
			$detail[] = $onAt;
		}
		
		$perPage = 50;
		$model = [];
		if ($user->role == User::ROLE_TEACHER) {
			$privateDetail->teacher_details = json_encode($detail);
			
			$model = PrivateModel::whereTeacherId($user->id)
				->orderBy('private.created_at', 'desc')
				->paginate($perPage);
			
		} else if ($user->role == User::ROLE_STUDENT) {
			$privateDetail->student_details = json_encode($detail);
			
			$model = PrivateModel::whereUserId($user->id)
				->orderBy('private.created_at', 'desc')
				->paginate($perPage);
			
		}
		
		$studentCheck = $teacherCheck = false;
		$studentDetails = json_decode($privateDetail->student_details, true);
		$teacherDetails = json_decode($privateDetail->teacher_details, true);
		foreach ($studentDetails as $detail) {
			if ($detail['check'] == 1) {
				$studentCheck = true;
			} else {
				$studentCheck = false;
			}
		}
		foreach ($teacherDetails as $detail) {
			if ($detail['check'] == 1) {
				$teacherCheck = true;
			} else {
				$teacherCheck = false;
			}
		}
		
		if (($studentCheck == true) && ($teacherCheck == true)) {
			$privateDetail->checklist = PrivateDetail::CHECK_TRUE;
			$privateDetail->checklist_at = Carbon::now()->toDateTimeString();
			$private->status = PrivateModel::STATUS_DONE;
			if ($user->role == User::ROLE_TEACHER) {
				$totalHistory = new \App\TeacherTotalHistory();
				$totalHistory->user_id = $user->id;
				$totalHistory->private_id = $private->id;
				$totalHistory->operation = \App\TeacherTotalHistory::OPERATION_PLUS;
				$totalHistory->total = $private->getTeacherTotalHonor();
				$totalHistory->status = \App\TeacherTotalHistory::STATUS_DONE;
				$totalHistory->created_at = $totalHistory->updated_at = Carbon::now()->toDateTimeString();
				$totalHistory->save();
				
				$teacherProfile = \App\TeacherProfile::whereUserId($user->id)->first();
				$teacherProfile->total = $teacherProfile->total + $totalHistory->total;
				$teacherProfile->total_updated_at = Carbon::now()->toDateTimeString();
				$teacherProfile->save();
			}
		} else {
			$privateDetail->checklist = PrivateDetail::CHECK_FALSE;
			$privateDetail->checklist_at = null;
			$private->status = PrivateModel::STATUS_ON_GOING;
		}
		
		$privateDetail->save();
		$private->save();
		
		$model = $model->toArray();
		$model['status'] = 201;
		$model['message'] = 'Checklist success';
		return response()->json($model, 201);
	}
	
	public function histories($uniqueNumber, Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();
		if ($user->unique_number != $uniqueNumber) {
			return response()->json([
				'status' => 404,
				'message' => 'User is not found',
			], 404);
		}
		
		$user = User::whereUniqueNumber($uniqueNumber)
			->roleApps()
			->appsActived()
			->first();
		if (!$user) {
			return response()->json([
				'status' => 404,
				'message' => 'User is not found',
			], 404);
		}
		
		$perPage = 50;
		
		$model = [];
		if ($user->role == User::ROLE_TEACHER) {
			$model = PrivateModel::with(['student', 'teacher'])->whereTeacherId($user->id)
				->orderBy('private.created_at', 'desc')
				->paginate($perPage);
		} else if ($user->role == User::ROLE_STUDENT) {
			$model = PrivateModel::with(['student', 'teacher'])->whereUserId($user->id)
				->orderBy('private.created_at', 'desc')
				->paginate($perPage);
		}
		
		$model = $model->toArray();
		$model['status'] = 200;
		$model['message'] = 'Success';

		return response()->json($model, 200);
	}
}