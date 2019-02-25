<?php

namespace App\Http\Controllers;

use App\Execution;
use App\Jobs\ParseJob;

class ExecutionController extends Controller
{
    public function index()
    {
        $executions = Execution::get();
        $canRun = $executions->filter(function ($execution) {
            return is_null($execution->getAttribute('finished_at'));
        })->count() === 0;
        return view('execution/index', [
            'executions' => $executions,
            'can_run' => $canRun,
        ]);
    }

    public function run()
    {
        if (!Execution::canBeRun()) {
            return [
                'success' => false,
                'error' => 'Есть выполняющиеся парсеры или парсеры, находящиеся в очереди на выполнение',
            ];
        }
        $execution = new Execution();
        if (!$execution->save()) {
            return [
                'success' => false,
                'error' => 'Не удалось добавить модель в БД',
            ];
        }
        if (!dispatch(new ParseJob($execution->getAttribute('id')))) {
            return [
                'success' => false,
                'error' => 'Не удалось добавить задачу в очередь',
            ];
        }

        return [
            'success' => true,
        ];
    }
}
