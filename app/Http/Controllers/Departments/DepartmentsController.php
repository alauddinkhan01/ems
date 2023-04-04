<?php

namespace App\Http\Controllers\Departments;

use File;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Departments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DepartmentsController extends Controller
{
    public function index()
    {
    //    return $user = User::with('latest_attendance')->where('id', auth()->user()->id)->first();

        $departments = Departments::paginate(10);

       return Inertia::render('Departments/Index',[
        'departments' => $departments
       ]);
    }

    public function create()
    {
        return Inertia::render('Departments/Create');
    }

    public function store(Request $request)
    {
        $department = new Departments();
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $fileName = time().$request->name.'.'.$extension;
        $file->move('Department',$fileName);
        $department->name = $request->name;
        $department->email = $request->email;
        $department->image = $fileName;
        $department->save();
        return redirect()->route('departments')->with('success','Department added successfully!');
    }

    public function updateField(Request $request)
    {
        if ($request->field == 'email') {
            $this->validate($request,[
                'value' => 'required|email|unique:departments,email,'.$request->id
            ]);
        }
        if ($request->field == 'name') {
            $this->validate($request,[
                'value' => 'required'
            ]);
        }
        $department = Departments::find($request->id);
        if ($request->file('image')) {
            if (File::exists(public_path('department/'.$department->image))) {
                File::delete(public_path('department/'.$department->image));
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().$request->name.'.'.$extension;
            $file->move('Department',$fileName);
            $department->image = $fileName;
            $department->save();
            return response()->json(['success'=>'Image Updated Successfully!','status'=>200]);
        }
        if ($request->field) {
            $filedName = $request->field;
            $department->$filedName = $request->value;
            $department->save();
        }
        return response()->json(['success'=>'Value Updated Successfully!','status'=>200]);
    }
    public function destroy($id)
    {
        $department = Departments::find($id);
        if (File::exists(public_path('department/'.$department->image))) {
            File::delete(public_path('department/'.$department->image));
        }
        $department->delete();
        return response()->json(['success'=>'Department deleted successfully!','status'=>200]);
    }
}
