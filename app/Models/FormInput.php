<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormInput extends Model
{
    use HasFactory;

    protected $table = 'forminputs';
    protected $fillable = [
        'berat_basah', 'drc', 'keterangan',
    ];
}
