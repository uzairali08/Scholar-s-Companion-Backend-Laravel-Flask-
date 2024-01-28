<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    //
    // function subjects(){
    //     return "Hello";
    // }

    function displaySubjects(){
        
        return Subject::all();
    }

    public function search($keyword)
    {
        // Search the subject table for the query
        $subject = Subject::where('subjectName', 'like', "%".$keyword."%")
        ->get();

        // Return the search results
        return $subject;
    }

}
