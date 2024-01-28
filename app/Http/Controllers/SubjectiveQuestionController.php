<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\subjectivequestion;
use App\Models\subjectivesolution;
use App\Models\Quranicsubject;
use App\Models\questionsrubric;
use App\Models\subjectivemark;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubjectiveQuestionController extends Controller
{
    //

    function addsubjectivequestions(Request $req)
    {
        try {
            $subjectivequestion = new subjectivequestion;
            $subjectivequestion->subjectId = $req->input('subjectId');
            $subjectivequestion->question = $req->input('question');
            $subjectivequestion->answer = $req->input('answer');
            $subjectivequestion->totalMarks = $req->input('totalMarks');
            $subjectivequestion->filePath = $req->file('file')->store('ResourceFiles');
            $subjectivequestion->definedBy = $req->input('definedBy');
            $subjectivequestion->save();

            // Success message
            $message = "Question Added Successfully";
            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            // Error message
            $message = "Question is not added";
            return response()->json(['message' => $message], 500);
        }
    }

    function getsubjectivequestionsdata($subjectId)
    {
        return subjectivequestion::where('subjectId', $subjectId)->get();
    }

    function deletesubjectivequestion(Request $req)
    {
        try {
            $id = $req->input('id'); // Extract the 'id' from the request

            $deleted = subjectivequestion::where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['message' => 'Question deleted successfully']);
            } else {
                return response()->json(['message' => 'Failed to delete question']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the question']);
        }
    }

    //to get the name of the course
    public function getcoursename($subjectId)
    {
        $subject = Quranicsubject::where('id', $subjectId)->first();

        if ($subject) {
            return response()->json(['subjectName' => $subject->subjectName]);
        }

        return response()->json(['error' => 'Subject not found'], 404);
    }


    function getallsubjectivedata($subjectId)
    {
        $data = DB::table('subjectivequestions')
            ->join('questionsrubrics', 'subjectivequestions.id', '=', 'questionsrubrics.questionId')
            ->select(
                'subjectivequestions.id',
                'subjectivequestions.subjectId',
                'subjectivequestions.question',
                'subjectivequestions.answer',
                'subjectivequestions.totalMarks',
                'subjectivequestions.filePath',
                'subjectivequestions.definedBy',
                'questionsrubrics.totalMarks as rubricTotalMarks',
                'questionsrubrics.maxMarks',
                'questionsrubrics.maxActivity',
                'questionsrubrics.averageMarks',
                'questionsrubrics.averageActivity',
                'questionsrubrics.minMarks',
                'questionsrubrics.minActivity',
                'questionsrubrics.definedBy as rubricDefinedBy'
            )
            ->where('subjectivequestions.subjectId', $subjectId)
            ->get();

        return $data;
    }

    public function downloadFile($id)
    {
        try {
            // Get the file path from the database
            $filePath = subjectivequestion::where('id', $id)->pluck('filePath')->first();

            // Check if file exists
            if (Storage::exists($filePath)) {
                // Return a download response
                return Storage::download($filePath);
            } else {
                $message = "File does not exist";
                return response()->json(['message' => $message], 404);
            }
        } catch (\Exception $e) {
            $message = "Something went wrong while trying to download the file";
            return response()->json(['message' => $message], 500);
        }
    }

    //saves the learner solution in databases
    function addsubjectivesolution(Request $req)
    {

        $subjectivesolution = new subjectivesolution;
        $subjectivesolution->courseId = $req->input('courseId');
        $subjectivesolution->learnerId = $req->input('learnerId');
        $subjectivesolution->questionId1 = $req->input('questionId1');
        $subjectivesolution->question1 = $req->input('question1');
        $subjectivesolution->solution1 = $req->input('solution1');
        $subjectivesolution->questionId2 = $req->input('questionId2');
        $subjectivesolution->question2 = $req->input('question2');
        $subjectivesolution->solution2 = $req->input('solution2');
        $subjectivesolution->questionId3 = $req->input('questionId3');
        $subjectivesolution->question3 = $req->input('question3');
        $subjectivesolution->solution3 = $req->input('solution3');
        $subjectivesolution->check = "unchecked";
        $subjectivesolution->save();
    }


    //get unchecked solutions list
    function getuncheckedsolutions(Request $request)
    {
        $courseId = $request->input('courseId');
        $learnerId = $request->input('learnerId');

        $solution = DB::table('subjectivesolutions')
            ->where('courseId', $courseId)
            ->where('learnerId', '!=', $learnerId)
            ->where('check', 'unchecked')
            ->first("learnerId");

        return response()->json($solution);
    }



    //get the subjective solution data
    function getsolution(Request $request)
    {
        $learnerId = $request->input('learnerId');
        $courseId = $request->input('courseId');

        $subjectivesolutions = DB::table('subjectivesolutions')
            ->where('learnerId', $learnerId)
            ->where('courseId', $courseId)
            ->first();

        return $subjectivesolutions;
    }



    public function getsubjectivesolution(Request $request)
    {

        $courseId = $request->input('courseId');
        $learnerId = $request->input('learnerId');

        $result = DB::table('subjectivesolutions')
            ->select(
                'subjectivesolutions.*',
                'sq1.answer AS answer1',
                'sq2.answer AS answer2',
                'sq3.answer AS answer3',
                'qr1.maxActivity AS maxActivity1',
                'qr1.averageActivity AS avgActivity1',
                'qr1.minActivity AS minActivity1',
                'qr2.maxActivity AS maxActivity2',
                'qr2.averageActivity AS avgActivity2',
                'qr2.minActivity AS minActivity2',
                'qr3.maxActivity AS maxActivity3',
                'qr3.averageActivity AS avgActivity3',
                'qr3.minActivity AS minActivity3'
            )
            ->join('subjectivequestions AS sq1', 'subjectivesolutions.questionId1', '=', 'sq1.id')
            ->join('subjectivequestions AS sq2', 'subjectivesolutions.questionId2', '=', 'sq2.id')
            ->join('subjectivequestions AS sq3', 'subjectivesolutions.questionId3', '=', 'sq3.id')
            ->join('questionsrubrics AS qr1', 'subjectivesolutions.questionId1', '=', 'qr1.questionId')
            ->join('questionsrubrics AS qr2', 'subjectivesolutions.questionId2', '=', 'qr2.questionId')
            ->join('questionsrubrics AS qr3', 'subjectivesolutions.questionId3', '=', 'qr3.questionId')
            ->where('subjectivesolutions.courseId', $courseId)
            ->where('subjectivesolutions.learnerId', $learnerId)
            ->limit(1)
            ->first();

        // Return the result
        return $result;
    }


    //add subjective test marks in database
    public function subjectivemarks(Request $req)
    {
        $subjectivemark = new subjectivemark;
        $subjectivemark->learnerId = $req->input('learnerId');
        $subjectivemark->courseId = $req->input('courseId');
        $subjectivemark->totalMarks = $req->input('totalMarks');
        $subjectivemark->obtainedMarks = $req->input('obtainedMarks');
        $subjectivemark->comment1 = $req->input('comment1');
        $subjectivemark->comment2 = $req->input('comment2');
        $subjectivemark->comment3 = $req->input('comment3');
        $subjectivemark->save();
    }


    //update the table field to checked
    public function updatecheck(Request $request)
    {
        $learnerId = $request->input('learnerId');
        $courseId = $request->input('courseId');

        DB::table('subjectivesolutions')
            ->where('learnerId', $learnerId)
            ->where('courseId', $courseId)
            ->update(['check' => 'checked']);

        return response()->json(['message' => 'Check column updated successfully.']);
    }




}