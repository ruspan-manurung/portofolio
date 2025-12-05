<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user->id ?? null,
            'user_name' => $this->user->name ?? null,
            'user_email' => $this->user->email ?? null,
            'name' => $this->document_name,
            'file_path' => $this->file_path,
            'fileSize' => Storage::size($this->file_path),
            'created_at' => $this->created_at,
            'recommendation' => $this->recomendation
                ? json_decode(
                    json_encode([
                        'id' => $this->recomendation->id,
                        'status' => $this->recomendation->status,
                        'reason' => $this->recomendation->reason,
                        'notes' => $this->recomendation->notes,
                        'recommended_method' => $this->recomendation->recommended_method,
                        'kelompok_pekerjaan' => $this->recomendation->kelompok_pekerjaan,
                        'competencyAnalysis' => $this->recomendation->kelompok_pekerjaan
                            ? collect(['A', 'B', 'C'])->map(function ($level) {
                                $kp = $this->recomendation->kelompok_pekerjaan;

                                // Tentukan metode tes berdasarkan kelompok_pekerjaan yang dipilih
                                $testMethod = match ($kp) {
                                    'A' => in_array($level, ['B', 'C']) ? 'Tes Tertulis' : 'Wawancara',
                                    'B' => in_array($level, ['A', 'B']) ? 'Wawancara' : 'Tes Tertulis',
                                    'C' => 'Wawancara',
                                    default => null,
                                };

                                $status = $testMethod === 'Tes Tertulis' ? 'required' : 'covered';

                                return [
                                    'level' => $level,
                                    'testMethod' => $testMethod,
                                    'status' => $status,
                                ];
                            })
                            : null,
                    ]),
                )
                : null,
        ];
    }
}