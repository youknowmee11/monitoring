<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataLahan extends Model
{
    use HasFactory;


    public static function getPetani($id_petani)
    {
        return self::where('id_user', $id_petani)->first();
    }
}