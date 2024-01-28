<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quranicsubject;

class QuranicSubjectController extends Controller
{
    //
    function quranicsubjects(){

        //return Quranicsubject::all();
        $QuranicSubjects = Quranicsubject::where('status', "Offered")->get();
        return $QuranicSubjects;

    }

    public function search($keyword)
    {
        // Search the subject table for the query
        $subject = Quranicsubject::where('subjectName', 'like', "%".$keyword."%")
        ->get();

        // Return the search results
        return $subject;
    }

    public function getSubjectName($id){

        $subjectId = Quranicsubject::where('id', '=', $id)
        ->get();

        return $subjectId;
    }
    

     //To display the list of record from database
     function subjectslist(){
        return Quranicsubject::all();
    }


    //get specific course from database using id
    function getsubject($id)
    {
        return Quranicsubject::where('id', $id)->first();
    }



     //to edit record in database
    function editsubject($id, Request $request)
    {
        $quranicSubject = Quranicsubject::where('id', '=', $id)->first();

        $quranicSubject->subjectName = $request->input('subjectName');
        $quranicSubject->subjectDescription = $request->input('subjectDescription');
        $quranicSubject->definedBy = $request->input('definedBy');
        $quranicSubject->status = $request->input('status');
        $quranicSubject->save();

        return $quranicSubject;
    }


    //To delete record from database
    function deletesubject($id){
        $result = Quranicsubject::where ('id',$id)->delete();
        if($result)
        {
            return ["result"=>"Course has been Deleted"];
        }
        else
        {
            return ["result"=>"Operation Failed"];
        }   
    }


    //get offered subjects
    function offeredsubjects (){
        return Quranicsubject :: where ('status', "Offered")->get();
    }

}
