<?php


namespace app\models\serviceTables;


use yii\db\ActiveRecord;

class Users extends ActiveRecord
{

	public function rules()
    {
        return [
            [
                [
                    'login',
                    'name',
                    'lastname',
                    'thirdname',
                    'fio',
                    'fullFio',
                    'locations',
                    'about',
                    'access',
                ],
                'trim',
            ],
            [['email'], 'email' ],
            [['pass'], 'string', 'max' => 256],
            [['login'], 'string', 'min' => 6, 'max' => 60],
            [['clients','role','permissions'], 'safe'],
        ];
    }

}