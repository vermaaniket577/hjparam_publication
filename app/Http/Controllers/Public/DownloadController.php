<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    /**
     * Download the official HJPARAM Copyright Transfer Form.
     */
    public function copyrightForm(): BinaryFileResponse
    {
        $filePath = public_path('downloads/HJPARAM_Copyright_Form.doc');
        
        if (!file_exists($filePath)) {
            abort(404, 'Copyright Form document not found.');
        }

        return response()->download($filePath, 'HJPARAM_Copyright_Transfer_Form.doc', [
            'Content-Type' => 'application/msword',
            'Content-Disposition' => 'attachment; filename="HJPARAM_Copyright_Transfer_Form.doc"',
        ]);
    }

    /**
     * Download the official HJPARAM Article Manuscript Template.
     */
    public function articleTemplate(): BinaryFileResponse
    {
        $filePath = public_path('downloads/HJPARAM_Article_Template.doc');

        if (!file_exists($filePath)) {
            abort(404, 'Article Template document not found.');
        }

        return response()->download($filePath, 'HJPARAM_Article_Manuscript_Template.doc', [
            'Content-Type' => 'application/msword',
            'Content-Disposition' => 'attachment; filename="HJPARAM_Article_Manuscript_Template.doc"',
        ]);
    }
}
