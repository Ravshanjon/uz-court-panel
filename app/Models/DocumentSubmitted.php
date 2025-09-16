<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentSubmitted extends Model

{
    protected $table = 'document_submitteds';

    protected $fillable = [
        'name'
    ];

}
