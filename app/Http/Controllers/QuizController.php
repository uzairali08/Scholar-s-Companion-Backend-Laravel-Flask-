<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\quizmark;
use App\Models\Interactivelearningresource;
use Illuminate\Support\Facades\DB;


class QuizController extends Controller
{
    //
    function displayquiz(){
        return Quiz::all();
    }


    function getquizdata($subjectId)
    {
        $data = DB::table('interactivelearningresources')
                ->where('subjectId', '=', $subjectId)
                ->get();
        
        return $data;
    }


    public function quizmarks(Request $req)
    {
        $quizmark= new quizmark;
        $quizmark->learnersId=$req->input('learnersId');
        $quizmark->coursesId=$req->input('coursesId');
        $quizmark->status=$req->input('status');
        $quizmark->totalMarks=$req->input('totalMarks');
        $quizmark->obtainedMarks=$req->input('obtainedMarks');
        $quizmark->save();
    }
    
    
    public function getQuizMarks(Request $request)
    {
        $coursesId = $request->input('coursesId');
        $learnersId = $request->input('learnersId');
    
        $quizmark = QuizMark::where('coursesId', $coursesId)
                            ->where('learnersId', $learnersId)
                            ->first();
    
        return $quizmark;
    }


}
