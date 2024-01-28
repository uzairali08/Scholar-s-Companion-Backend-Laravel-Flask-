<?php

use App\Http\Controllers\ResultController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\LearnerController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\QuranicSubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ObjectiveTestController;
use App\Http\Controllers\SubjectiveQuestionController;
use App\Http\Controllers\QuestionsRubricController;






/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//route to display all offered subjects
Route::get('quranicsubjects', [QuranicSubjectController::class, 'quranicsubjects']);

// route to search subject
Route::get('quranicsubjects/{keyword}', [QuranicSubjectController::class, 'search']);

// route plus function to display the content of subject
Route::get('/relatedayaats/{subjectID}', function (Request $request) {
    // Code to handle the API request
    $subjectID = $request->subjectID;
    $ayaats = DB::table('relatedayaats as t1')
        ->select('t1.ayatId', 't2.arabicText', 't2.eng_sahihInternational')
        ->join('ayaat as t2', 't1.ayatId', '=', 't2.ayatId')
        ->where('t1.subjectID', '=', $subjectID)
        ->get();

    // return response()->json($ayaats);
    return $ayaats;
});

Route::get('getSubjectName/{subjectID}', [QuranicSubjectController::class, 'getSubjectName']);




Route::get('displayquiz', [QuizController::class, 'displayquiz']);
Route::post('checkquiz', [QuizController::class, 'checkquiz']);





//Offered Courses (List/Delete/Edit) Admin Side 
Route::get('subjectslist', [QuranicsubjectController::class, 'subjectslist']);
Route::get('getsubject/{id}', [QuranicsubjectController::class, 'getsubject']);
Route::put('editsubject/{id}', [QuranicsubjectController::class, 'editsubject']);
Route::delete('deletesubject/{id}', [QuranicsubjectController::class, 'deletesubject']);
Route::get('offeredsubjects', [QuranicsubjectController::class, 'offeredsubjects']);


//To Register the Learner
Route::post('learnerregistration', [LearnerController::class, 'learnerregistration']);

//To Register the Scholar
Route::post('scholarregistration', [ScholarController::class, 'scholarregistration']);

//To Register the Admin
Route::post('adminregistration', [AdminController::class, 'adminregistration']);

//To Login the learner
Route::post('learnerlogin', [LearnerController::class, 'learnerlogin']);

//To Login the Scholar
Route::post('scholarlogin', [ScholarController::class, 'scholarlogin']);

//To Login the Scholar
Route::post('adminlogin', [AdminController::class, 'adminlogin']);

//To register a course
Route::post('coursesregistrations', [LearnerController::class, 'coursesregistrations']);

//To show a list of registered courses
Route::get('registeredcourseslist', [AdminController::class, 'registeredcourseslist']);

//To delete the registered courses
Route::delete('deleteregisteredcourse/{id}', [AdminController::class, 'deleteregisteredcourse']);


//To get a registered courses list of learner
Route::get('registeredcourses/{learnerId}', [LearnerController::class, 'registeredcourses']);

//Registered Scholars (List/Delete) Admin Side
Route::get('scholarslist', [ScholarController::class, 'scholarslist']);
Route::delete('deleteScholar/{id}', [ScholarController::class, 'deleteScholar']);



//Registered Learners (List/Delete) Admin Side
Route::get('learnerslist', [LearnerController::class, 'learnerslist']);
Route::delete('deletelearner/{id}', [LearnerController::class, 'deletelearner']);



// route to get data from 2 tables
Route::get('/relatedayaats/{subjectID}', function (Request $request) {
    // Your code to handle the API request goes here
    $subjectID = $request->subjectID;
    $ayaats = DB::table('relatedayaats as t1')
        ->select('t1.ayatId', 't2.arabicText', 't2.eng_sahihInternational', 't2.ur_AbuAaalaMaududi', 't2.surahNo', 't2.ayatNo')
        ->join('ayaats as t2', 't1.ayatId', '=', 't2.ayatId')
        ->where('t1.subjectID', '=', $subjectID)
        ->get();

    // return response()->json($ayaats);
    return $ayaats;
});


//route to get data from 2 tables for interactive learning quiz
// Route::get('/getquiz/{subjectID}', function (Request $request) {
//     $subjectID = $request->subjectID;
//     $ayaats = DB::table('relatedayaats as t1')
//         ->select('t1.ayatId', 't2.questionId', 't2.questionEnglish', 't2.questionUrdu', 't2.option1', 't2.option2', 't2.option3', 't2.option4')
//         ->join('interactivelearningresources as t2', 't1.ayatId', '=', 't2.ayatId')
//         ->where('t1.subjectID', '=', $subjectID)
//         ->get();

//     return $ayaats;
// });

//Route to data for Interactive Learning Session
Route::get('/data_content/{subjectId}', function ($subjectId) {
    $data_content = DB::table('relatedayaats as t1')
        ->leftJoin('interactivelearningresources as t2', 't1.ayatId', '=', 't2.ayatId')
        ->leftJoin('ayaats as t3', 't1.ayatId', '=', 't3.ayatId')
        ->where('t1.subjectID', '=', $subjectId)
        ->select(
            't1.ayatId',
            't2.questionId',
            't2.questionEnglish',
            't2.questionUrdu',
            't2.option1',
            't2.option2',
            't2.option3',
            't2.option4',
            't2.correctOption',
            't2.hint',
            't3.arabicText',
            't3.eng_sahihInternational',
            't3.ur_AbuAaalaMaududi',
            't3.surahNo',
            't3.ayatNo'
        )
        ->get();

    return $data_content;
});



// Route:: post('register',[UserController::class,'register']);
// Route:: post('login',[UserController::class,'login']);

Route::get('displayquiz', [QuizController::class, 'displayquiz']);

//To save the learner quiz data in table
Route::post('quizmarks', [QuizController::class, 'quizmarks']);

//get quiz marks from database
Route::post('getquizmarks', [QuizController::class, 'getquizmarks']);




// Route::post('login', [App\Http\Controllers\API\UserController::class, 'login'])->name('login');
// Route:: post('signin',[UserController::class,'signin']);










//Getting Quiz data
Route::get('getquizdata/{subjectId}', [QuizController::class, 'getquizdata']);








// Course Controller Class APIs

//To add course in database
Route::post('addcourse', [CourseController::class, 'addcourse']);
//To fetch surahs from database
Route::get('getsurahs', [CourseController::class, 'getsurahs']);
//fetch ayaat data from database where surah is selected
Route::get('getayaats/{surahNumber}', [CourseController::class, 'getayaats']);
//save the surahs and ayaats in dataabse using id
Route::post('addrelatedayaats', [CourseController::class, 'addrelatedayaats']);
//get related ayaats of selected course
Route::get('getrelatedayaats/{subjectId}', [CourseController::class, 'getrelatedayaats']);

//get ayaats data to display in add course section
Route::get('getayaatdata/{ayatId}', [CourseController::class, 'getayaatdata']);

//delete realted ayaats from database
Route::delete('deleterelatedayaat', [CourseController::class, 'deleterelatedayaat']);

//save the interactive learning data in DB
Route::post('progresstable', [CourseController::class, 'progresstable']);

//get the interactive learning data
Route::post('getprogress', [CourseController::class, 'getprogress']);

//update the interactive learning data in progress
Route::post('updateprogress', [CourseController::class, 'updateprogress']);








//Objective Test Controller API's 

//get all quranic subjects from database
Route::get('getquranicsubjects', [ObjectiveTestController::class, 'getquranicsubjects']);


//get surah name and number of selected quranic subject
Route::get('getsurahdata/{surahNo}', [ObjectiveTestController::class, 'getsurahdata']);

//get ayaat data of selected surah
Route::post('getayaatdata', [ObjectiveTestController::class, 'getayaatdata']);

//add objective test data in database
Route::post('addobjectivetest', [ObjectiveTestController::class, 'addobjectivetest']);

//get questions data of given subject id 
Route::get('getquestionsdata/{subjectId}', [ObjectiveTestController::class, 'getquestionsdata']);

//delete question interactive learning resources
Route::delete('deletequestion', [ObjectiveTestController::class, 'deletequestion']);










//subjective Questions Controller API's 

//routes to add Subjective Questions
Route::post('addsubjectivequestions', [SubjectiveQuestionController::class, 'addsubjectivequestions']);

//get subjective questions data of given subject id 
Route::get('getsubjectivequestionsdata/{subjectId}', [SubjectiveQuestionController::class, 'getsubjectivequestionsdata']);

//delete question interactive learning resources
Route::delete('deletesubjectivequestion', [SubjectiveQuestionController::class, 'deletesubjectivequestion']);

//get all subjective questions data of given subject id 
Route::get('getallsubjectivedata/{subjectId}', [SubjectiveQuestionController::class, 'getallsubjectivedata']);

//get course name 
Route::get('getcoursename/{subjectId}', [SubjectiveQuestionController::class, 'getcourseName']);

//saves the learner solution in databases
Route::post('addsubjectivesolution', [SubjectiveQuestionController::class, 'addsubjectivesolution']);

//get unchecked solution list
Route::post('getuncheckedsolutions', [SubjectiveQuestionController::class, 'getuncheckedsolutions']);

//get data for the peer to check the peer's work
Route::post('getsubjectivesolution', [SubjectiveQuestionController::class, 'getsubjectivesolution']);

//insert the subjective marks in database
Route::post('subjectivemarks', [SubjectiveQuestionController::class, 'subjectivemarks']);

//update the table field to checked
Route::post('updatecheck', [SubjectiveQuestionController::class, 'updatecheck']);






//Question Rubrics Controller API's

//routes to add Subjective Questions
Route::post('addquestionsrubric', [QuestionsRubricController::class, 'addquestionsrubric']);


//Result Controller API's

//get data for objective test
Route::post('objectiveresult', [ResultController::class, 'objectiveresult']);

//get data for objective test
Route::post('subjectiveresult', [ResultController::class, 'subjectiveresult']);



//Admin Controller

//route to get total numbers of users and courses
Route::get('/totalCount', [AdminController::class, 'totalCount']);
Route::get('/totalCountByYear', [AdminController::class, 'totalCountByYear']);


Route::get('downloadFile/{id}', [SubjectiveQuestionController::class, 'downloadFile']);

//get the subjective solution data
Route::post('getsolution', [SubjectiveQuestionController::class, 'getsolution']);