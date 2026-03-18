<?php

namespace app\models\serviceTables;
use yii\db\ActiveRecord;

class Images extends ActiveRecord
{

    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';


    public static function tableName()
    {
        return "images";
    }

    public function getStock()
    {
        return $this->hasOne(Stock::className(),['id'=>'pos_id']);
    }

    public function scenarios()
    {
        $columns = [
            'name',
            'status',
            'size',
            'pos_id',
        ];
        return [
            self::SCENARIO_ADD => $columns,
            self::SCENARIO_EDIT => $columns,
        ];
    }

    public function rules()
    {
        return [
            //rule1
            [
                [
                    'name',
                    'status',
                    'size',
                    'pos_id',
                ],
                'safe',
            ],
        ];
    }
}