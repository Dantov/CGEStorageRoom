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
            'item_name',
            'item_category',
            'item_size',
            'item_price',
            'item_price_rent',
            'project',
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
             'item_name'=> '№3D',
             'item_category'=> 'Тип Модели',
             'project'=> 'Project',
             'item_size'=> 'Вес Изделия',
             'item_price' => 'Стоимость Модели',
             'item_price_rent' => 'Стоимость Модели',
             'description'=> 'Примечания',
             'hashtags' => 'Теги для поиска',
             'date'=> 'Дата создания',
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'item_name',
                    'item_category',
                    'project',
                    'description',
                ],
                'required',
                'message' => 'Это поле нужно заполнить!'
            ],
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
        ];
    }

}