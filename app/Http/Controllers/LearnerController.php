<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Learner;
use App\Models\Coursesregistration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LearnerController extends Controller
{
    // To Register a Learner
    function learnerregistration(Request $req)
    {
        $learner= new Learner;
        $learner->firstName=$req->input('firstName');
        $learner->lastName=$req->input('lastName');
        $learner->email=$req->input('email');
        $learner->cnic=$req->input('cnic');
        $learner->password=Hash::make($req->input('password'));
        $learner->gender=$req->input('gender');
        $learner->nationality=$req->input('nationality');
        $learner->dateOfBirth=$req->input('dateOfBirth');
        $learner->save();
        return $learner;
    }


    //To login to the Learner System
    public function learnerlogin(Request $req)
    {

        $learner = Learner::where ('email', $req->email)->first();
        if (!$learner || !Hash::check($req->password, $learner->password))
        {
            return response([
                'error'=>["Email or Password is not Matched"]
            ]);
        }
        else{
            $userData = [
                'id' => $learner->id,
                'firstName' => $learner->firstName,
                'lastName' => $learner->lastName,
                'email' => $learner->email,
                'userrole' => "Learner",
                
                // add any other user information that you want to save in local storage
            ];

            return response([
                'success'=>["Login Successfully"],
                'user' => $userData
            ]);
        }
        return $learner;
    }



    

    public function coursesregistrations(Request $request)
    {
        $learnersId = $request->input('learnersId');
        $coursesId = $request->input('coursesId');
        
        // Check if the registration already exists
        $existingRegistration = Coursesregistration::where('learnersId', $learnersId)
            ->where('coursesId', $coursesId)
            ->first();

        if ($existingRegistration) {
            return response()->json([
                'success' => false,
                'message' => 'You have already registered for this course.'
            ]);
        }

        // Create a new registration
        $coursesregistrations = new Coursesregistration;
        $coursesregistrations->learnersId = $learnersId;
        $coursesregistrations->coursesId = $coursesId;
        $coursesregistrations->save();

        return response()->json([
            'success' => true,
            'message' => 'You have Successfully registered this course.'
        ]);
    }


    //To fetch all data from database
    function learnerslist(){
        return Learner::all();
    }

    //To delete record from database
    function deletelearner($id){
        $result = Learner::where ('id',$id)->delete();
        if($result)
        {
            return ["result"=>"Learner has been Deleted"];
        }
        else
        {
            return ["result"=>"Operation Failed"];
        }   
    }



    // Method to get a list of registered courses by a learner
    public function registeredcourses($learnerId)
    {
        // $learnerId = $req->input('learnerId');

        $registeredCourses = DB::table('coursesregistrations')
            ->select('coursesregistrations.id','coursesregistrations.coursesId','quranicsubjects.subjectName')
            ->join('quranicsubjects', 'coursesregistrations.coursesId', '=', 'quranicsubjects.id')
            ->where('coursesregistrations.learnersId', '=', $learnerId)
            ->get();

        return response()->json($registeredCourses);
    }


}
