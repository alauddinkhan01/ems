<?php

namespace App\Http\Controllers\Employes;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Departments;
use Illuminate\Support\Facades\Hash;
use File;

class EmployesController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::all();
        $searchedEmploye = $request->search;
        $departments = Departments::all();
        $employes = User::when($request->search, function ($query) use ($request) {
            $query->where('name', 'Like', '%' . $request->search . '%')
                ->orWhere('email', 'Like', '%' . $request->search . '%')
                ->orWhere(function ($q) use ($request) {
                    $q->whereHas('department', function ($q) use ($request) {
                        $q->where('name', 'Like', '%' . $request->search . '%');
                    });
                });
        })->whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'Employe');
            }
        )->with('roles', 'department')->paginate(10);
        return Inertia::render('Employes/Index', compact('roles', 'employes', 'departments','searchedEmploye'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'email|unique:users,email'
        ]);

        $user = new User();
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . $request->name . '.' . $extension;
        $file->move('users', $fileName); //move file to users folder in public (folder will be auto generated)
        $user->image = $fileName;
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->status = $request->status;
        $user->department_id = $request->department_id;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->assignRole($request->role);
        return response()->json(['success' => 'User added successfully!', 'status' => 200]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'email|unique:users,email,' . $request->id
        ]);
        $user = User::find($request->id);
        $file = $request->file('image');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . $request->name . '.' . $extension;
            $file->move('users', $fileName);
            $user->image = $fileName;
            $user->name = $request->name;
        }
        $user->mobile = $request->mobile;
        $user->status = $request->status;
        $user->department_id = $request->department_id;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $user->syncRoles($request->role);
        return response()->json(['success' => 'User added successfully!', 'status' => 200]);
    }

    public function destroy($id)
    {
        $employe = User::find($id);
        if (File::exists(public_path('users/' . $employe->image))) {
            File::delete(public_path('users/' . $employe->image));
        }
        $employe->delete();
        return response()->json(['success' => 'User deleted successfully!', 'status' => 200]);
    }
}
