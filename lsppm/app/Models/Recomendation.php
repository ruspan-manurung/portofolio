<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recomendation extends Model
{
    use HasFactory;

    protected $fillable = ['document_id', 'reason', 'status', 'validated_by', 'kelompok_pekerjaan', "notes"];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
