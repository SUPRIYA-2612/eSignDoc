<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;
    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $fillable=[
        'title',
        'original_file',
        'signed_file',
        'is_signed',
        'signature_type',
        'typed_signature',
        'drawn_signature_file',
];
}
