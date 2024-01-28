<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Scholar; 

class ScholarController extends Controller
{
    // To Register a Scholar
    public function scholarregistration(Request $req)
    {
        $scholar=new Scholar;
        $scholar->firstName=$req->input('firstName');
        $scholar->lastName=$req->input('lastName');
        $scholar->email=$req->input('email');
        $scholar->cnic=$req->input('cnic');
        $scholar->password=Hash::make($req->input('password'));
        $scholar->gender=$req->input('gender');
        $scholar->nationality=$req->input('nationality');
        $scholar->dateOfBirth=$req->input('dateOfBirth');
        $scholar->save();
        return $scholar;

    }


     //To login to the Scholar System
     public function scholarlogin(Request $req)
     {
 
         $scholar = Scholar::where ('email', $req->email)->first();
         if (!$scholar || !Hash::check($req->password, $scholar->password))
         {
             return response([
                 'error'=>["Email or Password is not Matched"]
             ]);
         }
         else{
             $userData = [
                 'id' => $scholar->id,
                 'firstName' => $scholar->firstName,
                 'lastName' => $scholar->lastName,
                 'email' => $scholar->email,
                 'userrole' => "Scholar",
                 
                 // add any other user information that you want to save in local storage
             ];
 
             return response([
                 'success'=>["Login Successfully"],
                 'user' => $userData
             ]);
         }
         return $scholar;
     }

     //To fetch all data from database
    function scholarslist(){
        return Scholar::all();
    }

    //To delete record from database
    function deleteScholar($id){
        $result = Scholar::where ('id',$id)->delete();
        if($result)
        {
            return ["result"=>"Scholar's record has been Deleted"];
        }
        else
        {
            return ["result"=>"Operation Failed"];
        }   
    }

}
