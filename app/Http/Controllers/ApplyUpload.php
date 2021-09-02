<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Forms;
use App\Models\StudentScholarship;

class ApplyUpload extends Controller
{
  public function createForm(){
    return view('apply');
  }
  

  public function fileUpload(Request $req){
    $student_id = Auth::user();
    $student_scholarship = StudentScholarship::where('student_id', '=', $student_id->student_id)->first();
    $req->validate([
    'file' => 'required|mimes:jpg,png,docx,csv,txt,xlx,xls,pdf|max:2048'
    ]);

        $fileModel = new Forms;

        if($req->file()) {
            $fileName = time().'_'.$req->file->getClientOriginalName();
            $filePath = $req->file('file')->move(public_path('files'), $fileName);
            $fileModel->name = time().'_'.$req->file->getClientOriginalName();
            $fileModel->file_path = $filePath;
            $fileModel->student_id = $student_id->student_id;
            $fileModel->inclusive_year = $student_scholarship->inclusive_year;
            $fileModel->tes = "TES Form";
            $fileModel->save();

           return back()
            ->with('success','File has been uploaded.')
            ->with('file', $fileName);
        }
   }

}