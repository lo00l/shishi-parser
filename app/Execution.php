<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Execution extends Model
{
    protected $table = 'af_parser_execution';

    public $timestamps = false;

    protected $dates = [
        'started_at',
        'finished_at',
    ];
}
