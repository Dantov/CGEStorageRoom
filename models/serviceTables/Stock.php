<?php

namespace app\models\serviceTables;
use yii\db\ActiveRecord;
use Yii;

class Stock extends ActiveRecord
{

    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';
    const SCENARIO_CLONE = 'clone';

    public $imgFor;

    public function getImages()
    {
        $session = Yii::$app->session;
        /*
        if ( $session['assist']['appPages']['main'] === true )
        {
            return $this->hasMany(Images::className(),['pos_id'=>'id'])
                ->select('id,img_name, main, pos_id')
                ->where(['=','main',1]);
        }
        */
        return $this->hasMany(Images::className(),['pos_id'=>'id']);
    }
    public function getMaterials()
    {
        $session = Yii::$app->session;
        if ( $session->get('sitepage') === 'view' )
        {
            return $this->hasMany(Materials::className(),['pos_id'=>'id'])
                ->orderBy(['part' => SORT_ASC]); //SORT_ASC SORT_DESC
        }
        return $this->hasMany(Materials::className(),['pos_id'=>'id']);
    }
    public function getGems()
    {
        $session = Yii::$app->session;
        if ( $session->get('sitepage') === 'view' )
        {
            return $this->hasMany(Gems::className(),['pos_id'=>'id'])
                ->orderBy(['size' => SORT_ASC]); //SORT_ASC SORT_DESC
        }
        return $this->hasMany(Gems::className(),['pos_id'=>'id']);
    }

    public function getD3_files()
    {
        return $this->hasMany(D3_files::className(),['pos_id'=>'id']);
    }


    public function scenarios()
    {
        $columns = [
            'id',
            'number_3d',
            'client',
            'modeller3d',
            'model_type',
            'size_range',
            'print_cost',
            'model_cost',
            'model_weight',
            'description',
            'hashtags',
            'model_status',
            'date',
            'create_date',
            'creator_id',
        ];
        return [
            self::SCENARIO_ADD => $columns,
            self::SCENARIO_EDIT => $columns,
            self::SCENARIO_CLONE => $columns,
        ];
    }

    public function attributeLabels()
    {
        return [
             'number_3d'=> '№3D',
             'model_type'=> 'Тип Модели',
             'client'=> 'Заказчик',
             'modeller3D'=> '3Д Модельер',
             'model_weight'=> 'Вес Изделия',
             'model_cost' => 'Стоимость Модели',
             'description'=> 'Примечания',
             'print_cost'=> 'Стоимость печати',
             'creator_name'=> 'Кто создал',
             'hashtags' => 'Теги для поиска',
             'date'=> 'Дата создания',
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'number_3d',
                    'modeller3d',
                    'model_type',
                    'model_weight',
                ],
                'required',
                'message' => 'Это поле нужно заполнить!'
            ],
            //rule2
            ['model_material', function ($attribute, $params) {
                // т.к. прототип строк тоже содержит атрибуты name и они находятся в форме
                // то приходят пустые строки
                $data = $this->$attribute;
                if ( !is_array($data) || empty($data) ) $this->addError($attribute, 'Нужно внести хоть один материал!');

                $materials = [];
                foreach ( $data as $mats )
                {
                    for( $i = 0; $i < count($mats); $i++ )
                    {
                        $materials[$i][] = $mats[$i];
                    }
                }
                // если хоть один инпут заполнен - считаем что строку можно вносить в БД
                foreach ( $materials as $material )
                {
                    foreach ( $material as $mat )
                    {
                        if ( !empty($mat) ) return;
                    }
                }
                $this->addError($attribute, 'Нужно внести хоть один материал!');
            }],
            //rule3
            [
                [
                    'imgFor',
                ],
                'required',
                'message' => 'Нужно внести хоть одину картинку!'
            ],
            //rule4
            [
                [
                    'number_3d',
                    'vendor_code',
                    'author',
                    'modeller3D',
                    'model_type',
                    'print_cost',
                    'description',
                    'size_range',
                    'labels',
                    'status',
                ],
                'trim',
            ],
            //rule5
            ['model_weight', 'number'],
            //rule6
            ['model_weight', function ($attribute, $params) {
                $data = $this->$attribute;
                if ( $data < 0 ) $this->addError($attribute, 'Вес должен быть положительным!');
            }],
        ];
    }

}