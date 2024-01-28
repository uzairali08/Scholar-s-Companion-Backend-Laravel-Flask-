<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\questionsrubric;


class QuestionsRubricController extends Controller
{
    //
    function addquestionsrubric(Request $req)
    {
        try {
            $questionsrubric = new questionsrubric;
            $questionsrubric->id = $req->input('id');
            $questionsrubric->questionId = $req->input('questionId');
            $questionsrubric->totalMarks = $req->input('totalMarks');
            $questionsrubric->maxMarks = $req->input('maxMarks');
            $questionsrubric->maxActivity = $req->input('maxActivity');
            $questionsrubric->averageMarks = $req->input('averageMarks');
            $questionsrubric->averageActivity = $req->input('averageActivity');
            $questionsrubric->minMarks = $req->input('minMarks');
            $questionsrubric->minActivity = $req->input('minActivity');
            $questionsrubric->definedBy = $req->input('definedBy');
            $questionsrubric->save();

            // Success message
            $message = "Rubric added successfully";
            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            // Error message
            $message = "Rubric adding failed";
            return response()->json(['message' => $message], 500);
        }
    }

}
