<?php

namespace App;

use Laravel\Lumen\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * @return array
     */
    public function getCurrentRoute()
    {
        return $this->currentRoute[1]['as'];
    }
}
