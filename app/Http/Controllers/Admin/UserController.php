<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Session;

class UserController extends Controller
{
	protected $rules = [
		'first_name' => 'required',
		'phone_number' => 'required|numeric',
		'address' => 'required',
		'email' => 'required|email|max:100|unique:user,email',
		'password' => 'required|min:6|max:255',
		'role' => 'required',
		'status' => 'required',
	];


	/**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
		
		$request['unique_number'] = User::generateUniqueNumber($request->role);
		$requestData = $request->all();
		$user = new User();
		$user->fill($requestData);
		$user->password = bcrypt($user->password);
		
		switch ($request->role) {
			case User::ROLE_SUPERADMIN :
				$user->save();
				break;
			case User::ROLE_TEACHER :
				$user->insertTeacher();
				break;
			case User::ROLE_STUDENT :
				$user->insertStudent();
				break;
		}
		
        Session::flash('flash_message', 'User added!');
        
        return redirect('admin/user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *Bank
     * @return View
     */
    public function show($id)
    {
        $model = User::findOrFail($id);

        return view('admin.user.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return View
     */
    public function edit($id)
    {
        $model = User::findOrFail($id);

        return view('admin.user.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param Request $request
     *
     * @return RedirectResponse|Redirector
     */
    public function update($id, Request $request)
    {
		$rules = $this->rules;
		unset($rules['email']);
		unset($rules['password']);
		$rules['email'] = 'required|email|max:100|unique:user,email,'.$id;
		$rules['password'] = 'required|min:6|max:255';
        $this->validate($request, $rules);
		
		$model = User::findOrFail($id);

		if (!empty($request->password)) {
			$request['password'] = bcrypt($request->password);
		} else {
			$request['password'] = $model->password;
		}
		$oldStatus = $model->status;
		$model->fill($request->all());
        $model->save();
		
		if ($model->role == User::ROLE_TEACHER) {
			if ($oldStatus != User::STATUS_ACTIVE) {
				if ($model->status == User::STATUS_ACTIVE) {
					$model->sendNotificationTeacherActive();
				}
			}
		}

        Session::flash('flash_message', 'User updated!');

        return redirect('admin/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return RedirectResponse|Redirector
     */
    public function destroy($id)
    {
		User::destroy($id);

        Session::flash('flash_message', 'User deleted!');

        return redirect('admin/user');
    }
	
	/**
	 * any data
	 */
	public function anyData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $model = User::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'user.*'])->orderBy('user.role');

         $datatables = app('datatables')->of($model)
			->editColumn('first_name', function ($model) {
				return $model->getFullName();
			})
			->editColumn('status', function ($model) {
				return $model->getStatusLabel();
			})
			->editColumn('role', function ($model) {
				return $model->getRoleLabel();
			})
            ->addColumn('action', function ($model) {
                return '<a href="user/'.$model->id.'" class="btn btn-xs btn-success rounded" data-toggle="tooltip" title="" data-original-title="'. trans('systems.edit') .'"><i class="fa fa-eye"></i></a> '
						. '<a href="user/'.$model->id.'/edit" class="btn btn-xs btn-primary rounded" data-toggle="tooltip" title="" data-original-title="'. trans('systems.edit') .'"><i class="fa fa-pencil"></i></a> '
						. '<a onclick="deleteData('.$model->id.')" class="btn btn-xs btn-danger rounded" data-toggle="tooltip" title="" data-original-title="'. trans('systems.delete') .'"><i class="fa fa-trash"></i></a>';
            });

        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        if ($range = $datatables->request->get('range')) {
            $rang = explode(":", $range);
            if($rang[0] != "Invalid date" && $rang[1] != "Invalid date" && $rang[0] != $rang[1]){
                $datatables->whereBetween('user.created_at', ["$rang[0] 00:00:00", "$rang[1] 23:59:59"]);
            }else if($rang[0] != "Invalid date" && $rang[1] != "Invalid date" && $rang[0] == $rang[1]) {
                $datatables->whereBetween('user.created_at', ["$rang[0] 00:00:00", "$rang[1] 23:59:59"]);
            }
        }
		
        return $datatables->make(true);
    }
}
