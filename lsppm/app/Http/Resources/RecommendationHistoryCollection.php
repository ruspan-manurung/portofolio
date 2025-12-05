<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class RecommendationHistoryCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return $this->collection
            ->groupBy(fn($item) => $item->user->id)
            ->map(function ($documents) {
                $user = $documents->first()->user;

                // Ambil semua kelompok pekerjaan yang valid
                $kelompokPekerjaan = $documents
                ->filter(function ($doc) {
                    return isset($doc->recomendation) && $doc->recomendation->status === 'approved';
                })
                ->map(function ($doc) {
                    return $doc->recomendation->kelompok_pekerjaan ?? null;
                })
                ->filter()
                ->values();
                // $kelompokPekerjaan = $documents->pluck('recomendation.kelompok_pekerjaan')->filter()->values();

                // Jika tidak ada rekomendasi valid
                $finalKP = $kelompokPekerjaan->isEmpty() ? null : $kelompokPekerjaan->map(fn($kp) => ['A' => 0, 'B' => 1, 'C' => 2][$kp])->average();

                // Mapping ke final kelompok pekerjaan
                $finalLevel = match (true) {
                    is_null($finalKP) => null,
                    $finalKP < 0.5 => 'A',
                    $finalKP < 1.5 => 'B',
                    default => 'C',
                };

                // Definisikan test method berdasarkan kelompok pekerjaan final
                $competencyAnalysis = collect(['A', 'B', 'C'])->map(function ($level) use ($finalLevel) {
                    $testMethod = match ($finalLevel) {
                        'A' => in_array($level, ['B', 'C']) ? 'Tes Tertulis' : 'Wawancara',
                        'B' => in_array($level, ['A', 'B']) ? 'Wawancara' : 'Tes Tertulis',
                        'C' => 'Wawancara',
                        default => '-',
                    };

                    $status = $testMethod === 'Tes Tertulis' ? 'required' : 'covered';

                    return [
                        'level' => $level,
                        'testMethod' => $testMethod,
                        'status' => $status,
                    ];
                });

                $labelMap = [
                    'A' => '(A) Pemasaran Produk Investasi Dasar',
                    'B' => '(B) Pembukaan Rekening Efek',
                    'C' => '(C) Pendokumentasian',
                ];

                return [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'finalDecision' => $finalLevel ? $labelMap[$finalLevel] ?? $finalLevel : 'Pending',
                    'competencyLevels' => $competencyAnalysis,
                    'documents' => $documents
                        ->map(function ($doc) {
                            $recommendation = $doc->recomendation;
                            return [
                                'id' => $doc->id,
                                'file_path' => $doc->file_path,
                                'document_name' => $doc->document_name,
                                'uploadDate' => $doc->created_at->toDateString(),
                                'processedDate' => optional($recommendation)->created_at?->toDateString(),
                                'status' => $recommendation?->status,
                                'aiRecommendation' => $recommendation?->reason,
                                'kelompok_pekerjaan' => $recommendation?->kelompok_pekerjaan,
                                'assessorName' => $recommendation?->validator->name ?? 'N/A',
                                'fileSize' => Storage::exists($doc->file_path) ? Storage::size($doc->file_path) : 0,
                            ];
                        })
                        ->values(),
                ];
            })
            ->values()
            ->all();
    }
}