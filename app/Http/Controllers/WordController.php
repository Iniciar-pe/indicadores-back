<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function downloadWord() {

        try {

            $variable = "texto loren prueba";

            $template = new \PhpOffice\PhpWord\TemplateProcessor(documentTemplate: 'app/avatars/descarga.docx');
            $template->setValue(search: 'text', replace: $variable);

            $tempFile = tempnam(sys_get_temp_dir(), prefix: 'PHPWord');
            $template->saveAs($tempFile);

            $headers = [
                "Content-Type: application/octet-stream",
            ];

            return response()->download($tempFile, name: 'document.docx')->deleteFileAfterSend(shouldDelete: true);


        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            return back($e->getCode());
        }

    }


}
