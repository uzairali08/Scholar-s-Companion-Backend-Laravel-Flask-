<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Scholar;
use App\Models\Learner;
use App\Models\Quranicsubject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // To Register a Admin
    function adminregistration(Request $req)
    {
        $admin= new Admin;
        $admin->firstName=$req->input('firstName');
        $admin->lastName=$req->input('lastName');
        $admin->email=$req->input('email');
        $admin->cnic=$req->input('cnic');
        $admin->password=Hash::make($req->input('password'));
        $admin->gender=$req->input('gender');
        $admin->nationality=$req->input('nationality');
        $admin->dateOfBirth=$req->input('dateOfBirth');
        $admin->save();
        return $admin;
    }


    //To login to the Admin System
    public function adminlogin(Request $req)
    {

        $admin = Admin::where ('email', $req->email)->first();
        if (!$admin || !Hash::check($req->password, $admin->password))
        {
            return response([
                'error'=>["Email or Password is not Matched"]
            ]);
        }
        else{
            $userData = [
                'id' => $admin->id,
                'firstName' => $admin->firstName,
                'email' => $admin->email,
                'userrole' => "Admin",
                
                // add any other user information that you want to save in local storage
            ];

            return response([
                'success'=>["Login Successfully"],
                'user' => $userData
            ]);
        }
        return $learner;

    }

    // Method to get a list of registered courses
    public function registeredcourseslist()
    {
        $registeredCourses = DB::table('learners')
            ->select('coursesregistrations.id', 'learners.firstName', 'learners.lastName', 'learners.email', 'quranicsubjects.subjectName')
            ->join('coursesregistrations', 'learners.id', '=', 'coursesregistrations.learnersId')
            ->join('quranicsubjects', 'coursesregistrations.coursesId', '=', 'quranicsubjects.id')
            ->get();

        return response()->json($registeredCourses);
    }

    // Method to delete registered courses
    public function deleteregisteredcourse($id)
    {
        $result = DB::table('coursesregistrations')->where('id', $id)->delete();
        if ($result) {
            return ["result" => "Course has been deleted"];
        } else {
            return ["result" => "Operation failed"];
        }
    }

    //Method to get total number of scholars, learners and subjects
    public function totalCount()
    {
        $scholarCount = Scholar::count();
        $learnerCount = Learner::count();
        $subjectCount = Quranicsubject::count();

        return response()->json([
            'scholarCount' => $scholarCount,
            'learnerCount' => $learnerCount,
            'subjectCount' => $subjectCount,
        ]);
    }

    //Method to total number of scholars, learner and subjects in each year
    public function totalCountByYear()
    {
    $totalsByYear = DB::table('learners')
        ->select(DB::raw('YEAR(created_at) AS year'), DB::raw('COUNT(*) AS learnerCount'))
        ->groupBy(DB::raw('YEAR(created_at)'))
        ->get();

    $totalsByYear = $totalsByYear->keyBy('year');

    $scholarsByYear = DB::table('scholars')
        ->select(DB::raw('YEAR(created_at) AS year'), DB::raw('COUNT(*) AS scholarCount'))
        ->groupBy(DB::raw('YEAR(created_at)'))
        ->get();

    $scholarsByYear = $scholarsByYear->keyBy('year');

    $subjectsByYear = DB::table('quranicsubjects')
        ->select(DB::raw('YEAR(created_at) AS year'), DB::raw('COUNT(*) AS subjectCount'))
        ->groupBy(DB::raw('YEAR(created_at)'))
        ->get();

    $subjectsByYear = $subjectsByYear->keyBy('year');

    $result = [];
    $years = array_unique(array_merge($totalsByYear->keys()->toArray(), $scholarsByYear->keys()->toArray(), $subjectsByYear->keys()->toArray()));

    foreach ($years as $year) {
        $result[$year] = [
            'learnerCount' => $totalsByYear[$year]->learnerCount ?? 0,
            'scholarCount' => $scholarsByYear[$year]->scholarCount ?? 0,
            'subjectCount' => $subjectsByYear[$year]->subjectCount ?? 0,
        ];
    }

    return response()->json($result);
    }

}