<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Relatedayaat;
use App\Models\Surah;
use App\Models\Ayaat;
use App\Models\Interactivelearningresource;


class ObjectiveTestController extends Controller
{
    //
    function getquranicsubjects()
    {
        $QuranicSubjects = Quranicsubject::where('status', "Offered")->get();
        return $QuranicSubjects;
    }


    function getsurahdata($subjectId)
    {    
        $result = RelatedAyaat::select('surahs.surahName', 'relatedayaats.surahNo')
        ->distinct()
        ->join('surahs', 'relatedayaats.surahNo', '=', 'surahs.surahNumber')
        ->where('subjectId', '=', $subjectId)
        ->orderBy('relatedayaats.surahNo', 'asc')
        ->get();

        return $result;
    
    }

    function getayaatdata(Request $request)
    {
        $subjectId = $request->input('subjectId');
        $surahNo = $request->input('surahNo');


        $result = Ayaat::whereIn('ayatId', function ($query) use($subjectId, $surahNo) {
        $query->select('ayatId')
        ->from('relatedayaats')
        ->where('subjectId', $subjectId)
        ->where('surahNo', $surahNo);
        })
        ->get();

        return $result;
    }

   

    public function addobjectivetest(Request $request) {

        $surahNo = $request->input('surahNo');
        $ayatNo = $request->input('ayatNo');
        $ayatId = $request->input('ayatId');
        $questionEnglish = $request->input('questionEnglish');
        $option1 = $request->input('option1');
        $option2 = $request->input('option2');
        $option3 = $request->input('option3');
        $option4 = $request->input('option4');
        $hint = $request->input('hint');
        $correctOption = $request->input('correctOption');
        $subjectId = $request->input('subjectId');

        // $data = $request->all();
        $interactivelearningresource = new Interactivelearningresource();
        $interactivelearningresource->surahNo = $surahNo;
        $interactivelearningresource->ayatNo = $ayatNo;
        $interactivelearningresource->ayatId = $ayatId;
        $interactivelearningresource->questionEnglish = $questionEnglish;
        $interactivelearningresource->option1 = $option1;
        $interactivelearningresource->option2 = $option2;
        $interactivelearningresource->option3 = $option3;
        $interactivelearningresource->option4 = $option4;
        $interactivelearningresource->hint = $hint;
        $interactivelearningresource->correctOption = $correctOption;
        $interactivelearningresource->subjectId = $subjectId;
        $interactivelearningresource->save();
    
        return response()->json(['success' => true]);
    }

    function getquestionsdata($subjectId)
    {
        $result = Interactivelearningresource::where('subjectId', $subjectId)->get();
        return $result;        
    }

    function deletequestion(Request $request)
    {
        $subjectId = $request->input('subjectId');
        $ayatId = $request->input('ayatId');

        Interactivelearningresource::where('subjectId', '=', $subjectId)
            ->where('ayatId', '=', $ayatId)
            ->delete();

        return response()->json(['message' => 'Relatedayaat record deleted.']);

    }
}
