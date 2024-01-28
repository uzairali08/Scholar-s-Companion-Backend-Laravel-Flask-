<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    //
    function objectiveresult(Request $request)
    {
        $courseId = $request->input('courseId');
        $learnerId = $request->input('learnerId');

        $quizMarks = DB::table('quizmarks')
            ->where('coursesId', $courseId)
            ->where('learnersId', $learnerId)
            ->first();

        return $quizMarks;

    }

    function subjectiveresult(Request $request)
    {
        $courseId = $request->input('courseId');
        $learnerId = $request->input('learnerId');

        $subjectiveMarks = DB::table('subjectivemarks')
            ->where('courseId', $courseId)
            ->where('learnerId', $learnerId)
            ->first();

        return $subjectiveMarks;
    }
}