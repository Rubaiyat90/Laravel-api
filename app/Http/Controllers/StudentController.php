<?php

namespace App\Http\Controllers;

use App\Models\Student;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(){
        $students = Student::all();

        if($students->count() > 0){
            return response()->json([
                'status' => 200,
                'students' => $students
            ], 200);
        } else{
            return response()->json([
                'status' => 500,
                'message' => 'Not found'
            ], 500);
        }
    }

    public function store(Request $request){

        $validate = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191',
            'phone' => 'required|string|digits:11',
            'department' => 'required|string|max:191'
        ]);

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department
        ]);
        if($student){
            return response()->json([
                'status' => 200,
                'students' => 'Students created'
            ], 200);
        } else{
            return response()->json([
                'status' => 500,
                'message' => 'error'
            ], 500);
        }
    }

    public function show($id){
        $student = Student::find($id);

        if($student){
            return response()->json([
                'status' => 200,
                'student' => $student
            ], 200);
        } else{
            return response()->json([
                'status' => 404,
                'message' => 'not found'
            ], 200);
        }
    }

    public function edit($id){
        $student = Student::find($id);

        if($student){
            return response()->json([
                'status' => 200,
                'student' => $student
            ], 200);
        } else{
            return response()->json([
                'status' => 404,
                'message' => 'not found'
            ], 404);
        }
    }

    public function update(Request $request, $id){
        $student = Student::find($id);

        $validate = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191',
            'phone' => 'required|string|digits:11',
            'department' => 'required|string|max:191'
        ]);

        if($student){
            $student->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'department' => $request->department
            ]);

            return response()->json([
                'status' => 200,
                'student' => 'Student updated'
            ], 200);
        } else{
            return response()->json([
                'status' => 404,
                'message' => 'something wrong'
            ], 404);
        }
    }

    public function destroy($id){
        $student = Student::find($id);
        if($student){
            $student->delete();
            return response()->json([
                'status' => 200,
                'student' => $student
            ], 200);
        } else{
            return response()->json([
                'status' => 404,
                'message' => 'not found'
            ], 200);
        }
    }
}
