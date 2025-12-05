<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentResource;
use App\Http\Resources\RecommendationHistoryCollection;
use App\Models\AuditLog;
use App\Models\Document;
use App\Models\Recomendation;
use App\Models\Summary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;

class RecomendationController extends Controller
{
    public function index()
    {
        $documents = Document::with('user')->with('recomendation')->latest()->get();

        return Inertia::render('Recommendation', [
            'documents' => DocumentResource::collection($documents)->resolve(),
        ]);
    }

    // public function generateAllFromUser(array $documentNames)
    // {
    //     $documents = Document::where('user_id', auth()->id())
    //         ->whereIn('document_name', $documentNames)
    //         ->get();

    //     if ($documents->isEmpty()) {
    //         return response()->json(['error' => 'Tidak ada dokumen ditemukan.'], 404);
    //     }

    //     $combinedText = '';
    //     $pdfParser = new \Smalot\PdfParser\Parser();

    //     foreach ($documents as $doc) {
    //         $path = storage_path("app/private/{$doc->file_path}");
    //         $text = '[Dokumen gagal dibaca atau kosong]';

    //         try {
    //             if ($doc->document_type === 'pdf') {
    //                 $pdf = $pdfParser->parseFile($path);
    //                 $text = trim($pdf->getText());
    //             } elseif ($doc->document_type === 'docx') {
    //                 $phpWord = \PhpOffice\PhpWord\IOFactory::load($path);
    //                 $text = '';
    //                 foreach ($phpWord->getSections() as $section) {
    //                     foreach ($section->getElements() as $element) {
    //                         if (method_exists($element, 'getText')) {
    //                             $text .= $element->getText() . "\n";
    //                         }
    //                     }
    //                 }
    //                 $text = trim($text);
    //             }
    //         } catch (\Exception $e) {
    //             $text = '[Gagal membaca isi dokumen]';
    //         }

    //         if (empty($text)) {
    //             $text = '[Dokumen tidak mengandung teks]';
    //         }

    //         $combinedText .= "- Dokumen: {$doc->document_name}\n";
    //         $combinedText .= "Isi:\n" . Str::limit(strip_tags($text), 1500) . "\n\n";
    //     }

    //     $finalPrompt = <<<EOT
    //     Berikut adalah isi beberapa dokumen peserta. Harap analisis ISI NYATA dokumen (bukan nama file) untuk menentukan kelompok pekerjaan yang sesuai dan metode ujian berdasarkan indikator berikut:

    //     1. Kelompok Pekerjaan A - Pemasaran Produk Investasi Dasar
    //     Indikator: Marketing Tools, Marketing Pipeline, SK Marketing Officer >1 Tahun

    //     2. Kelompok Pekerjaan B - Pembukaan Rekening Efek
    //     Indikator: Formulir Pembukaan Rekening Efek, SK Marketing Officer >1 Tahun

    //     3. Kelompok Pekerjaan C - Pendokumentasian
    //     Indikator: Marketing Report, SK Kerja Senior Marketing, Sertifikat Kompetensi WPPE/WPPE-P, Lisensi WPPE/WPPE-P, SPT Mengajar WPPE/WPPE-P

    //     📌 Sistem Hierarki:
    //     - A < B < C
    //     - Jika peserta hanya memiliki dokumen A dan/atau B, maka C tetap *Tes Tertulis*
    //     - Jika peserta memiliki dokumen kelompok C, maka A dan B cukup *Wawancara*
    //     - Metode hanya: *Tes Tertulis* atau *Wawancara*

    //     Tentukan untuk setiap dokumen:
    //     - Kelompok (A, B, atau C) berdasarkan ISI
    //     - Metode ujian
    //     - Alasan logis

    //     Berikan hasil dalam format JSON berikut:
    //     {
    //     "rekomendasi": [
    //         {"dokumen": "NamaFile.pdf", "kelompok": "B", "metode": "Tes Tertulis", "alasan": "..."}
    //     ],
    //     "kesimpulan": "..."
    //     }

    //     Berikut isi dokumennya:
    //     $combinedText
    //     EOT;

    //     $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
    //         'model' => 'gpt-4-1106-preview',
    //         'messages' => [['role' => 'user', 'content' => $finalPrompt]],
    //     ]);

    //     $content = $response->json('choices.0.message.content');
    //     Log::info('Response dari OpenAI:', ['response_text' => $content]);

    //     if (!$content) {
    //         return response()->json(['error' => 'Gagal menerima hasil dari OpenAI'], 500);
    //     }

    //     // Bersihkan dari potensi format Markdown ```json ... ```
    //     $content = trim($content);
    //     if (Str::startsWith($content, '```json')) {
    //         $content = trim(Str::replaceFirst('```json', '', $content));
    //         $content = trim(Str::replaceLast('```', '', $content));
    //     }

    //     $json = json_decode($content, true);
    //     if (!$json || !isset($json['rekomendasi'])) {
    //         return response()->json([
    //             'error' => 'Hasil dari OpenAI bukan format JSON yang valid.',
    //             'raw_response' => $content,
    //         ], 422);
    //     }

    //     foreach ($json['rekomendasi'] as $item) {
    //         $document = $documents->firstWhere('document_name', $item['dokumen']);
    //         if ($document) {
    //             Recomendation::create([
    //                 'document_id' => $document->id,
    //                 // 'recommended_method' => $item['metode'] ?? 'Tes Tertulis',
    //                 'kelompok_pekerjaan' => !empty($item['kelompok']) ? $item['kelompok'] : 'A',
    //                 'reason' => $item['alasan'] ?? '-',
    //                 'status' => 'pending',
    //             ]);
    //         }
    //     }

    //     Summary::updateOrCreate([
    //         'user_id' => auth()->id(),
    //     ], [
    //         'kesimpulan' => $json['kesimpulan']
    //     ]);

    //     return response()->json([
    //         'message' => 'Rekomendasi berhasil dibuat',
    //         'kesimpulan' => $json['kesimpulan'] ?? '-',
    //         'raw' => $json,
    //     ]);
    // }

    public function generateAllFromUser(array $documentNames)
    {
        $documents = Document::where('user_id', auth()->id())
            ->whereIn('id', $documentNames)
            ->get();

        if ($documents->isEmpty()) {
            return response()->json(['error' => 'Tidak ada dokumen ditemukan.'], 404);
        }

        // Hilangkan duplikat berdasarkan nama file
        $documents = $documents->unique('document_name')->values();

        // Hanya ambil judul dokumen
        $combinedTitles = '';
        foreach ($documents as $doc) {
            $combinedTitles .= "- {$doc->document_name}\n";
        }

        // Prompt — tegas minta OUTPUT JSON SAJA, dengan indikator yang jelas
        $finalPrompt = <<<EOT
        Anda bertugas mengkategorikan dokumen BERDASARKAN JUDUL DOKUMEN SAJA.

        **SISTEM PERINTAH (WAJIB DITAATI):**
        1) KELUARKAN **HANYA** satu valid JSON (tidak ada teks bebas, tidak ada header, tidak ada penjelasan).
        2) Format harus persis seperti di contoh "CONTOH_JSON" di bawah.
        3) Satu objek rekomendasi per judul dokumen (tidak duplikat).
        4) SELALU sertakan field "metode". Metode hanya boleh: "Tes Tertulis" atau "Wawancara".
        5) Gunakan aturan hierarki:
        - Kelompok A < Kelompok B < Kelompok C
        - Jika peserta memiliki dokumen kelompok C, maka A dan B cukup Wawancara.
        - Jika hanya A dan/atau B, maka gunakan Tes Tertulis untuk yang tidak memiliki C.
        6) Analisis **BERDASARKAN JUDUL**: deteksi indikator di judul sesuai daftar indikator di bawah.
        7) Jika yakin pilih kelompok sesuai indikator. Jika ragu, pilih kelompok yang paling konservatif berdasarkan indikator judul.
        8) **Gunakan bahasa yang mudah dipahami pengguna** untuk field "alasan" dan "kesimpulan".
            - Jangan gunakan istilah teknis atau terlalu formal.
            - Jelaskan alasan singkat dengan gaya ramah, misalnya:
                "Dokumen ini berhubungan dengan pemasaran, jadi masuk kelompok A."
                "Judul menunjukkan adanya lisensi resmi, sehingga masuk kelompok C."

        **INDIKATOR (PASTIKAN MODEL MENGGUNAKAN DAFTAR INI):**

        1. Kelompok Pekerjaan A - Pemasaran Produk Investasi Dasar
        Indikator judul: "Marketing Tools", "Marketing Pipeline", "SK Marketing Officer >1 Tahun"

        2. Kelompok Pekerjaan B - Pembukaan Rekening Efek
        Indikator judul: "Formulir Pembukaan Rekening Efek", "SK Marketing Officer >1 Tahun"

        3. Kelompok Pekerjaan C - Pendokumentasian
        Indikator judul: "Marketing Report", "SK Kerja Senior Marketing", "Sertifikat Kompetensi WPPE/WPPE-P", "Lisensi WPPE/WPPE-P", "SPT Mengajar WPPE/WPPE-P"

        **CONTOH_JSON** (model harus meniru struktur ini persis):
        {
        "rekomendasi": [
            {"dokumen": "NamaFile.pdf", "kelompok": "A|B|C", "metode": "Tes Tertulis|Wawancara", "alasan": "...."}
        ],
        "kesimpulan": "..."
        }

        Berikut daftar judul dokumen (ANALISIS HANYA DARI JUDUL):
        $combinedTitles
        EOT;

        $messages = [
            ['role' => 'system', 'content' => 'You must output ONLY a single valid JSON object exactly as requested by the user. No extra text, no markdown, no commentary.'],
            ['role' => 'user', 'content' => $finalPrompt],
        ];

        $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4-1106-preview',
            'messages' => $messages,
            'temperature' => 0,
            'max_tokens' => 800,
        ]);

        $content = $response->json('choices.0.message.content');
        Log::info('Response dari OpenAI:', ['response_text' => $content]);

        if (!$content) {
            return response()->json(['error' => 'Gagal menerima hasil dari OpenAI'], 500);
        }

        // Bersihkan code fences jika ada, dan trim
        $content = trim($content);
        if (Str::startsWith($content, '```')) {
            $content = preg_replace('/^```(?:json)?\s*/i', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);
            $content = trim($content);
        }

        // Decode JSON
        $json = json_decode($content, true);

        // if (!$json || !isset($json['rekomendasi']) || !is_array($json['rekomendasi'])) {
        //     return response()->json([
        //         'error' => 'Hasil dari OpenAI bukan format JSON yang valid atau tidak sesuai struktur yang diminta.',
        //         'raw_response' => $content,
        //     ], 422);
        // }
        // Kalau hasil JSON nggak sesuai, bikin default
        if (!$json || !isset($json['rekomendasi']) || !is_array($json['rekomendasi']) || empty($json['rekomendasi'])) {
            $json['rekomendasi'] = [];
            foreach ($documents as $doc) {
                $json['rekomendasi'][] = [
                    'dokumen' => $doc->document_name,
                    'kelompok' => 'A',
                    'metode' => 'Tes Tertulis',
                    'alasan' => 'Default karena rekomendasi AI tidak tersedia.'
                ];
            }
            $json['kesimpulan'] = 'Default karena rekomendasi AI tidak tersedia.';
        }

        // Simpan rekomendasi (hindari duplikat penyimpanan)
        $savedDocs = [];
        foreach ($json['rekomendasi'] as $item) {
            $docName = $item['dokumen'] ?? null;
            if (!$docName) continue;

            if (in_array($docName, $savedDocs)) continue;

            $document = $documents->firstWhere('document_name', $docName);
            if ($document) {
                Recomendation::create([
                    'document_id' => $document->id,
                    'kelompok_pekerjaan' => !empty($item['kelompok']) ? $item['kelompok'] : 'A',
                    'reason' => $item['alasan'] ?? ($item['reason'] ?? '-'),
                    'status' => 'pending',
                ]);
                $savedDocs[] = $docName;
            }
        }

        Summary::updateOrCreate([
            'user_id' => auth()->id(),
        ], [
            'kesimpulan' => $json['kesimpulan'] ?? null
        ]);

        return response()->json([
            'message' => 'Rekomendasi berhasil dibuat',
            'kesimpulan' => $json['kesimpulan'] ?? '-',
            'raw' => $json,
        ]);
    }


    public function validateRecommendation(Recomendation $recomendation, Request $request)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'required_if:status,rejected|string|nullable|max:1000',
        ]);

        $recomendation->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'validated_by' => auth()->id(),
        ]);

        $recomendation->load('document');

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Validate Recommendation',
            'target' => $recomendation->document->name,
            'status' => 'Success',
            'details' => 'Recommendation has been ' . $request->status,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Recommendation validated');
    }

    public function recommendationHistory()
    {
        $documents = Document::with(['user', 'recomendation.validator'])
            ->latest()
            ->get();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Accessed Recommendation Hisory Page',
            'target' => 'Recommendation Hisory Page',
            'status' => 'Success',
            'details' => 'User successfully accessed the recommendation Hisory page.',
            'ip_address' => request()->ip(),
        ]);

        return Inertia::render('RecommendationHistory', [
            'documents' => (new RecommendationHistoryCollection($documents))->resolve(),
        ]);
    }
}
