<?php

namespace App\Http\Controllers;

use Ufee\Amo\AmoAPI;

class AmoAuth
{
    protected $amoAPI;

    public function connect()
    {
        $config = [
            'id' => '31726946',
            'domain' => 'iliaosipov134.amocrm.ru',
            'login' => 'iliaosipov.134@gmail.com',
            'hash' => '13041adb18ebb5c6519f35169e76faa18a2319db',
            'zone' => 'ru',
            'timezone' => 'Europe/Moscow',
            'lang' => 'ru',
        ];

        AmoAPI::setInstance($config);
        $this->amoAPI = AmoAPI::getInstance($config['id']);
    }

    public function getAmoAPI()
    {
        return $this->amoAPI;
    }
}
