<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\CreateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['users'] = User::all();

        if($request->ajax()){
            return $this->getUserData($request);
        }

        return view('user.list', $data);
    }

    private function getUserData(Request $request)
    {
        $limit = $request->input('length');
        $start = $request->input('start');

        // Base query with LEFT JOIN to get the role name
        $query = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.is_verify',
            'roles.name as role_name',
            'users.created_at'
        )->leftJoin('roles', 'users.role_id', '=', 'roles.id');

        // Apply search
        if (!empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('roles.name', 'like', "%{$search}%");
            });
        }

        // Apply ordering
        if (!empty($request->input('order'))) {
            $columnIndex = $request->input('order')['column'];
            $columnName = $request->input('columns')[$columnIndex]['data'];
            $columnSortOrder = $request->input('order')['dir'];

            $orderByColumn = match ($columnName) {
                'name' => 'users.name',
                'email' => 'users.email',
                'is_verify' => 'users.is_verify',
                'role' => 'role_name', // Sort by role name
                'created_at' => 'users.created_at',
                default => 'users.created_at',
            };

            $query->orderBy($orderByColumn, $columnSortOrder);
        } else {
            $query->orderBy('users.created_at', 'desc');
        }

        // Count records
        $totalFilteredRecords = $query->count();
        $totalRecords = User::count();

        // Apply pagination
        $users = $query->offset($start)->limit($limit)->get();

        // Transform data to match DataTable structure
        $userData = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_verify' => $user->is_verify,
                'role' => $user->role_name,
                'created_at' => $user->created_at->format('m-d-Y'), // Ensure correct format
            ];
        });

        // Return JSON response
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFilteredRecords,
            "data" => $userData
        ]);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateVerify(Request $request)
    {
        if(empty($request->userId) || empty($request->is_verify)){
            return response()->json(array('success' => false, 'message' => 'The verify user not has been updated.'));
        }

        $user = User::find($request->userId);
        $user->is_verify = 0;
        if($request->is_verify == 'true'){
            $user->is_verify = 1;
        }
        $user->save();
        return response()->json(array('success' => true, 'message' => 'The verify user has been updated.'));

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No user IDs provided.']);
        }
        User::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => 'Users deleted successfully.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginAjax(Request $request)
    {
        $userId = $request->input('userId');
        if ($userId) {
            $currentUser = auth()->user();
            if ($currentUser->role->name === 'superadmin') {
                auth()->logout();
                auth()->loginUsingId($userId);
                return response()->json(['success' => true, 'message' => 'User logged in successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Only superadmin can perform this action.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'User ID not provided.']);
        }
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateUserRequest $request)
    {
        if(!empty($request->user_id)){
            $user = User::find($request->user_id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->role_id = $request->input('role');
            if($request->has('password')){
                $user->password = bcrypt($request->input('password'));
            }
            $user->save();
            return response()->json(['success' => true, 'message' => 'User update successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'User not update.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeNew(CreateUserRequest $request)
    {

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role');
        $user->is_verify = 1;
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return response()->json(['success' => true, 'message' => 'User create successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function formDetail(Request $request)
    {
        $user = new User();

        if(!empty($request->userId)){
            $user = User::find($request->userId);
        }

        $roles = Role::all()->pluck('name', 'id');


        return response()->json([
            'success' => true,
            'user' => $user,
            'html' => view('user.form', compact('user', 'roles'))->render()
        ]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
