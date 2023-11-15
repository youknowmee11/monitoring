<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataLahan extends Model
{
    use HasFactory;


    public static function getPetani($id_petani)
    {
        return self::where('id_user', $id_petani)->first();
    }

    public function jenis_jagung(): BelongsTo
    {
        return $this->belongsTo(JenisJagung::class, 'id_jenis_jagung', 'id');
    }
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'code_alat', 'code_alat');
    }
}
