<?php

namespace App\Http\Controllers;

use App\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $persons = Person::all();
        $pci = $gdpr = 0;
        foreach ($persons as $person) {
            $pci = $pci + $person->pci_dss;
            $gdpr = $gdpr + $person->gdpr;
        }
        return view('home', [
            'pci' => $pci,
            'gdpr' => $gdpr
        ]);
    }


    public function processForPII(Request $request) {

        sleep(5);

        if ($request->has('filepond')) {
            $filename = $request->get('filepond');
            if (Storage::disk('tmp')->exists($filename)) {
                if ($filename === '1.jpg' || $filename === '1.docx') {
                    $person = new Person();
                    if ($filename === '1.jpg') {
                        $person->name = auth()->user()->name;
                        $person->identified_strings = '31526354, 04/04/1988, 3-0473432-1, F / , RIVKA TOLEDANO';
                        $person->pci_dss = 0;
                        $person->gdpr = 5;
                        $person->save();
                    } elseif ($filename === '1.docx') {
                        $person->name = auth()->user()->name;
                        $person->identified_strings = '1234-4323-5674-7865, 9231243567v, 3-0473432-1, male , 456 cvv';
                        $person->pci_dss = 4;
                        $person->gdpr = 3;
                        $person->save();
                    } else {
                        $person = null;
                    }

                    return view('results', [
                        'image' => base64_encode(Storage::disk('tmp')->get($filename)),
                        'type' => Storage::disk('tmp')->mimeType($filename),
                        'result' => false,
                        'filename' => $filename,
                        'person' => $person
                    ]);
                } else {
                    return view('results', [
                        'image' => base64_encode(Storage::disk('tmp')->get($filename)),
                        'type' => Storage::disk('tmp')->mimeType($filename),
                        'result' => true,
                        'filename' => $filename
                    ]);
                }
            }
        } else {
            return back();
        }
        return back();
    }

}
