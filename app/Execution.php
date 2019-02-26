<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Execution
 * @package App
 * @property $startedAt
 * @property $finishedAt
 */
class Execution extends Model
{
    protected $table = 'af_parser_execution';

    public $timestamps = false;

    protected $dates = [
        'started_at',
        'finished_at',
    ];

    public static function canBeRun()
    {
        return self::whereNull('finished_at')->get()->count() === 0;
    }

    public function getStartTimeAttribute()
    {
        if (is_null($this->getAttribute('started_at'))) {
            return 'В очереди на выполнение';
        }

        return $this->getAttribute('started_at')->format('d.m.Y H:i:s');
    }

    public function getFinishTimeAttribute()
    {
        if (is_null($this->getAttribute('started_at')) && is_null($this->getAttribute('finished_at'))) {
            return 'В очереди на выполнение';
        }

        if (is_null($this->getAttribute('finished_at'))) {
            return 'Выполняется сейчас';
        }

        return $this->getAttribute('finished_at')->format('d.m.Y H:i:s');
    }

    public function getIsSuccessAttribute()
    {
        if (is_null($this->getAttribute('success')) && is_null($this->getAttribute('started_at'))) {
            return 'В очереди на выполнение';
        }

        if (is_null($this->getAttribute('success'))) {
            return 'Выполняется сейчас';
        }

        return $this->getAttribute('success') ? 'Да' : 'Нет';
    }
}
