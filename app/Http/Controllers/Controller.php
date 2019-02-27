<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    abstract protected function getModelClass();

    abstract protected function getValidatorRules();

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), $this->getValidatorRules());
        if ($validator->fails()) {
            return $this->error($validator->errors()->toJson());
        }

        $modelClass = $this->getModelClass();
        $model = $modelClass::whereKey($id)->first();
        if (is_null($model)) {
            return $this->error('Неверный ID');
        }

        $model->fill($request->all());
        $model->save();

        return $this->success();
    }

    public function updateAll(Request $request)
    {
        $modelClass = $this->getModelClass();
        $data = $request->all();
        foreach ($data as $id => $row)
        {
            $model = $modelClass::whereKey($id)->first();
            if (!is_null($model)) {
                $model->fill($row);
                $model->save();
            }
        }

        return $this->success();
    }

    private function success()
    {
        return [
            'success' => true,
        ];
    }

    private function error($message)
    {
        return [
            'success' => false,
            'error' => $message,
        ];
    }
}
