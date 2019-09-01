<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function process(Request $request) {
        $validator = Validator::make($request->all(), [
            'filepond' => 'required|file|mimes:jpeg,bmp,png,txt,docx,pdf,text|max:10000'
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors()->first()], 500);
        }

        if (!file_exists(storage_path('app/tmp'))) {
            mkdir(storage_path('app/tmp'), 0777, true);
        }
        if (!$request->hasFile('filepond')) {
            return response('err', 500);
        }
        $file = $request->file('filepond');
        $filename = $file->getClientOriginalName();
        $file->storeAs('tmp/', $filename);
        return $filename;
    }

    public function delete(Request $request) {
        $filename = trim($request->getContent());
        $filename = preg_replace('/[^a-zA-Z0-9\-\._]/','', $filename);
        if (Storage::disk('tmp')->exists($filename)) {
            Storage::disk('tmp')->delete($filename);
            return response('ok', 201);
        } else {
            return response('Error', 422);
        }
    }
}
