<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Quranicsubject;
use App\Models\Surah;
use App\Models\Ayaat;
use App\Models\Relatedayaat;
use App\Models\progress;
use Illuminate\Support\Facades\DB;


class CourseController extends Controller
{
    // To add Course in database
    function addcourse(Request $req)
    {
        $course = new Quranicsubject;

        $course->subjectName = $req->input('subjectName');
        $course->subjectDescription = $req->input('subjectDescription');
        $course->ontologylevel1 = $req->input('ontologylevel1');
        $course->ontologylevel2 = $req->input('ontologylevel2');
        $course->ontologylevel3 = $req->input('ontologylevel3');
        $course->ontologylevel4 = $req->input('ontologylevel4');
        $course->definedBy = $req->input('definedBy');
        $course->status = $req->input('status');
        $course->save();

        return $course;

    }

    //To fetch surahs from database
    function getsurahs()
    {
        return Surah::all();
    }

    //fetch ayaat data from database where surah is selected
    function getayaats($surahNo)
    {
        return Ayaat::where('surahNo', $surahNo)->get();
    }

    //save the surahs and ayaats in dataabse using id
    function addrelatedayaats(Request $request)
    {
        $subjectId = $request->input('subjectId');
        $surahNo = $request->input('surahNo');
        $ayatId = $request->input('ayatId');

        // check if a relatedayaat with the same subjectId, surahNo, and ayatId already exists
        $relatedayaat = Relatedayaat::where('subjectId', $subjectId)
            ->where('surahNo', $surahNo)
            ->where('ayatId', $ayatId)
            ->first();

        if ($relatedayaat) {
            // a relatedayaat with the same subjectId, surahNo, and ayatId already exists
            return response()->json(['message' => 'Relatedayaat already exists.']);
        }

        // create a new relatedayaat if one does not already exist
        $relatedayaat = new Relatedayaat;
        $relatedayaat->subjectId = $subjectId;
        $relatedayaat->ayatId = $ayatId;
        $relatedayaat->surahNo = $surahNo;
        $relatedayaat->relevenceScore = 0;
        $relatedayaat->save();

        return response()->json(['message' => 'Relatedayaat created successfully.']);
    }

    function getrelatedayaats($subjectId)
    {
        $data = RelatedAyaat::where('subjectid', $subjectId)
            ->join('ayaats', 'relatedayaats.ayatId', '=', 'ayaats.ayatid')
            ->join('surahs', 'ayaats.surahno', '=', 'surahs.surahNumber')
            ->get();
        return $data;
    }


    public function deleterelatedayaat(Request $request)
    {
        $subjectId = $request->input('subjectId');
        $ayatId = $request->input('ayatId');

        Relatedayaat::where('subjectId', '=', $subjectId)
            ->where('ayatId', '=', $ayatId)
            ->delete();

        return response()->json(['message' => 'Relatedayaat record deleted.']);
    }






    //interactive learning progress data management
    function progresstable(Request $request)
    {
        $courseId = $request->input('courseId');
        $learnerId = $request->input('learnerId');

        // Check if progress entry already exists
        $existingProgress = progress::where('courseId', $courseId)
            ->where('learnerId', $learnerId)
            ->first();

        if ($existingProgress) {
            return response()->json(['message' => 'Progress already exists']);
        }

        $progress = new Progress;
        $progress->courseId = $courseId;
        $progress->learnerId = $learnerId;
        $progress->currentAyat = 0;
        $progress->percentage = 0;
        $progress->save();

        return response()->json(['message' => 'Progress inserted successfully']);
    }

    function getprogress(Request $request)
    {
        $courseId = $request->input('courseId');
        $learnerId = $request->input('learnerId');

        $progressData = DB::table('progress')
            ->where('courseId', $courseId)
            ->where('learnerId', $learnerId)
            ->first();

        return $progressData;
    }

    function updateprogress(Request $request)
    {
        $courseId = $request->input('courseId');
        $learnerId = $request->input('learnerId');
        $currentAyat = $request->input('currentAyat');
        $percentage = $request->input('percentage');

        DB::table('progress')
            ->where('courseId', $courseId)
            ->where('learnerId', $learnerId)
            ->update([
                'currentAyat' => $currentAyat,
                'percentage' => $percentage,
            ]);

        return response()->json(['message' => 'Progress updated successfully']);
    }


}