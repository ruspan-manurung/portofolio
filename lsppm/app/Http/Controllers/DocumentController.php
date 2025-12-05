<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentResource;
use App\Models\AuditLog;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DocumentController extends Controller
{
    public function documentUpload()
    {
        // $documents = Document::with('user')->with('recomendation')->latest()->get();

        $user = Auth::user();

        // Cek apakah user adalah participant
        if ($user->role === 'participant') {
            // Ambil hanya dokumen milik dia
            $documents = Document::with(['user', 'recomendation'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        } else {
            // Admin atau peran lainnya
            $documents = Document::with(['user', 'recomendation'])
                ->latest()
                ->get();
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Accessed Document Upload Page',
            'target' => 'Document Upload Page',
            'status' => 'Success',
            'details' => 'User successfully accessed the document upload page.',
            'ip_address' => request()->ip(),
        ]);

        return Inertia::render('DocumentUpload', [
            'documents' => DocumentResource::collection($documents)->resolve(),
        ]);
    }

    public function index()
    {
        $documents = Document::with('user')->with('recomendation')->latest()->get();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Accessed Document Management Page',
            'target' => 'Document Management Page',
            'status' => 'Success',
            'details' => 'User successfully accessed the document management page.',
            'ip_address' => request()->ip(),
        ]);

        return Inertia::render('Document', [
            'documents' => DocumentResource::collection($documents)->resolve(),
        ]);
    }

    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'files.*' => 'required|file|mimes:pdf,doc,docx|max:10240',
    //     ]);

    //     $uploadedDocuments = [];
    //     $readableDocuments = []; // Dokumen yang berhasil dibaca
    //     $pdfParser = new \Smalot\PdfParser\Parser();

    //     foreach ($request->file('files') as $file) {
    //         $path = $file->store('documents');
    //         Log::info('File disimpan:', ['original' => $file->getClientOriginalName(), 'path' => $path]);

    //         $document = Document::create([
    //             'user_id' => auth()->id(),
    //             'document_name' => $file->getClientOriginalName(),
    //             'document_type' => $file->getClientOriginalExtension(),
    //             'file_path' => $path,
    //         ]);

    //         AuditLog::create([
    //             'user_id' => auth()->id(),
    //             'action' => 'Uploaded Document',
    //             'target' => $document->document_name,
    //             'status' => 'Success',
    //             'details' => 'Document uploaded and saved to storage.',
    //             'ip_address' => $request->ip(),
    //         ]);

    //         $uploadedDocuments[] = $document;

    //         // Cek apakah dokumen bisa dibaca
    //         try {
    //             $fullPath = storage_path("app/private/{$path}");
    //             $ext = strtolower($file->getClientOriginalExtension());

    //             $text = '';

    //             if ($ext === 'pdf') {
    //                 $pdf = $pdfParser->parseFile($fullPath);
    //                 $text = trim($pdf->getText());
    //             } elseif ($ext === 'docx') {
    //                 $phpWord = \PhpOffice\PhpWord\IOFactory::load($fullPath);
    //                 foreach ($phpWord->getSections() as $section) {
    //                     foreach ($section->getElements() as $element) {
    //                         if (method_exists($element, 'getText')) {
    //                             $text .= $element->getText() . "\n";
    //                         }
    //                     }
    //                 }
    //                 $text = trim($text);
    //             }

    //             // Kalau isi terbaca, tambahkan ke daftar yang dikirim ke AI
    //             if (!empty($text) && strlen($text) > 50) {
    //                 $readableDocuments[] = $document->id;
    //             } else {
    //                 Log::warning("Dokumen tidak terbaca: " . $document->document_name);
    //                 AuditLog::create([
    //                     'user_id' => auth()->id(),
    //                     'action' => 'Unreadable Document',
    //                     'target' => $document->document_name,
    //                     'status' => 'Warning',
    //                     'details' => 'Document uploaded but content could not be read or is empty.',
    //                     'ip_address' => $request->ip(),
    //                 ]);
    //             }
    //         } catch (\Exception $e) {
    //             Log::error("Gagal membaca isi dokumen: " . $document->document_name . " - " . $e->getMessage());

    //             AuditLog::create([
    //                 'user_id' => auth()->id(),
    //                 'action' => 'Unreadable Document',
    //                 'target' => $document->document_name,
    //                 'status' => 'Warning',
    //                 'details' => 'Document uploaded but content could not be read or is empty.',
    //                 'ip_address' => $request->ip(),
    //             ]);
    //         }
    //     }

    //     // Kalau tidak ada dokumen terbaca, jangan kirim ke AI
    //     $recommendation = null;
    //     if (!empty($readableDocuments)) {
    //         $recommendation = app(RecomendationController::class)->generateAllFromUser($readableDocuments);
    //         AuditLog::create([
    //             'user_id' => auth()->id(),
    //             'action' => 'Generated Recommendation',
    //             'target' => implode(', ', $readableDocuments),
    //             'status' => 'Success',
    //             'details' => 'AI recommendation generated for uploaded documents.',
    //             'ip_address' => $request->ip(),
    //         ]);
    //     }

    //     return redirect()->route('documents.upload')->with([
    //         'message' => 'Upload & rekomendasi berhasil',
    //         'documents' => $uploadedDocuments,
    //         'recommendation' => $recommendation ?? null,
    //     ]);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $uploadedDocuments = [];

        foreach ($request->file('files') as $file) {
            $path = $file->store('documents');

            Log::info('File disimpan:', [
                'original' => $file->getClientOriginalName(),
                'path' => $path,
            ]);

            $document = Document::create([
                'user_id' => auth()->id(),
                'document_name' => $file->getClientOriginalName(),
                'document_type' => $file->getClientOriginalExtension(),
                'file_path' => $path,
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'Uploaded Document',
                'target' => $document->document_name,
                'status' => 'Success',
                'details' => 'Document uploaded and saved to storage.',
                'ip_address' => $request->ip(),
            ]);

            $uploadedDocuments[] = $document;
        }

        // Kirim semua dokumen yang diupload ke AI (berdasarkan judul saja)
        $recommendation = app(RecomendationController::class)->generateAllFromUser(collect($uploadedDocuments)->pluck('id')->toArray());

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Generated Recommendation',
            'target' => implode(', ', collect($uploadedDocuments)->pluck('document_name')->toArray()),
            'status' => 'Success',
            'details' => 'AI recommendation generated for uploaded documents.',
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('documents.upload')
            ->with([
                'message' => 'Upload & rekomendasi berhasil',
                'documents' => $uploadedDocuments,
                'recommendation' => $recommendation,
            ]);
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Deleted Document',
            'target' => $document->document_name,
            'status' => 'Success',
            'details' => 'Document deleted successfully.',
            'ip_address' => request()->ip(),
        ]);

        if ($document->file_path && Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        // Hapus semua recommendation yang berelasi
        $document->recomendation()->delete();

        // Hapus dokumennya
        $document->delete();

        return redirect()->back()->with('success', 'Dokumen dan rekomendasi berhasil dihapus.');
    }

    public function download($filename)
    {
        if (str_contains($filename, '..')) {
            abort(403, 'Invalid path.');
        }

        $path = storage_path("app/private/{$filename}");

        if (!file_exists($path)) {
            abort(404, 'File not found.');
        }

        return response()->download($path);
    }
}