<?php

namespace App\Models;

use App\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Signature extends Model
{
    use SoftDeletes, HasFactory, HasAuthor;

    protected $connection = 'mongodb';

    protected $fillable = [ 'user_id', 'email', 'document', 'size',
                            'client_id','created_by', 'updated_by' ];
}
