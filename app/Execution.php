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
    /**
     * @var string
     */
    protected $table = 'af_parser_execution';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $dates = [
        'started_at',
        'finished_at',
    ];

    /**
     * @return bool
     */
    public static function canBeRun()
    {
        return self::whereNull('finished_at')->get()->count() === 0;
    }

    /**
     * @return string
     */
    public function getStartTimeAttribute()
    {
        if (is_null($this->getAttribute('started_at'))) {
            return 'В очереди на выполнение';
        }

        return $this->getAttribute('started_at')->format('d.m.Y H:i:s');
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
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
