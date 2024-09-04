<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MotherUnit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

}
