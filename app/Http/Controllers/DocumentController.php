<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use setasign\Fpdi\Fpdi;
use Pdf;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Document;

class DocumentController extends Controller
{
    public function dashboard() {
        $documents = Document::latest()->get();
        return view('dashboard', compact('documents'));
    }
    
    public function create() {
        return view('upload');
    }
    
    public function store(Request $request) {
        $request->validate(['title' => 'required', 'document' => 'required|file|mimes:pdf,docx']);
        if ($request->hasFile('document')) {
            $image = $request->file('document');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/document/'), $imageName);
       
        }
        Document::create([
            'title' => $request->title,
            'original_file' => $imageName
        ]);
    
        return redirect()->route('dashboard')->with('success', 'Document uploaded successfully.');
    }
    public function signForm($id)
{
    $document = Document::findOrFail($id);
    return view('sign', compact('document'));
}

public function submitSignature(Request $request, $id)
{
    try {
        $doc = Document::findOrFail($id);
        $type = $request->signature_type;

        if (!$type || !in_array($type, ['typed', 'drawn'])) {
            return redirect()->back()->with('error', 'Please select a signature type.');
        }
        $doc->signature_type = $type;

        if ($type === 'typed') {
            if (!$request->typed_signature || trim($request->typed_signature) === '') {
                return redirect()->back()->with('error', 'Typed signature is required.');
            }

            $doc->typed_signature = $request->typed_signature;
        } else {
            if (!$request->drawn_signature || trim($request->drawn_signature) === '') {
                return redirect()->back()->with('error', 'Drawn signature is required.');
            }

            $base64 = str_replace('data:image/png;base64,', '', $request->drawn_signature);
            $imageName = 'sign_' . time() . '.png';
            $imagePath = public_path("storage/sign/{$imageName}");
            file_put_contents($imagePath, base64_decode($base64));
            $doc->drawn_signature_file = $imageName;
        }

        
        $originalFile = $doc->original_file;
        $extension = strtolower(pathinfo($originalFile, PATHINFO_EXTENSION));
        $uploadedPath = public_path('storage/document/' . $originalFile);

    
        if ($extension === 'docx') {
            $phpWord = WordIOFactory::load($uploadedPath);
            $tempPdfPath = public_path('storage/temp_pdf_' . time() . '.pdf');

            $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
            $htmlPath = public_path('storage/temp_html_' . time() . '.html');
            $htmlWriter->save($htmlPath);

            $dompdf = new Dompdf();
            $dompdf->loadHtml(file_get_contents($htmlPath));
            $dompdf->setPaper('A4');
            $dompdf->render();
            file_put_contents($tempPdfPath, $dompdf->output());

            $uploadedPath = $tempPdfPath;
        }

        $signedPdfName = 'signed_' . time() . '.pdf';
        $signedPdfPath = public_path('storage/sign-document/' . $signedPdfName);

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($uploadedPath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            if ($pageNo == $pageCount) {
                if ($type === 'typed') {
                    $pdf->SetFont('Helvetica', 'I', 14);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY($size['width'] - 70, $size['height'] - 30);
                    $pdf->Write(0, $doc->typed_signature);
                } else {
                    $imagePath = public_path('storage/sign/' . $doc->drawn_signature_file);
                    $pdf->Image($imagePath, $size['width'] - 60, $size['height'] - 40, 40);
                }
            }
        }

        $pdf->Output($signedPdfPath, 'F');

        $doc->signed_file = $signedPdfName;
        $doc->is_signed = true;
        $doc->save();
        return redirect()->route('preview', $doc->id)->with('success', 'Document signed successfully.');

    } catch (\Exception $e) {
     
        \Log::error('Signature submission error: ' . $e->getMessage());

        return redirect()->back()->with('error', 'An error occurred while submitting the signature. Please try again.');
       
    }
}

    public function preview($id)
{
    $document = Document::findOrFail($id);

    if (!$document->signed_file || !public_path('storage/sign-document' . $document->signed_file)) {
        return redirect()->back()->with('error', 'Signed file not found.');
    }

    return view('preview', compact('document'));
}
public function download($id)
{
    $document = Document::findOrFail($id);
    $filePath = public_path('storage/sign-document/' . $document->signed_file);

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'Signed file not found.');
    }

    return response()->download($filePath);
}


}