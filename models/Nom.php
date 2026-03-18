<?php
namespace app\models;

use app\models\serviceTables\{Service_data};
use app\models\User;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveQuery;

class Nom extends Common
{
    protected array $projects;

    public function getModelTypes() : array
    {
         return Service_data::find()->where(['tab' => 'model_type'])->asArray()->all();
    }

    public function getProjects( string $tab ) : array
    {
        if ( isset($this->projects) && is_array($this->projects) && !empty($this->projects) ) {
            return $this->projects;
        } else {
            return $this->projects = Service_data::find()->where(['tab' => 'project'])->asArray()->all(); 
        }
    }
    
    protected function addSearch()
    {
        $session = Yii::$app->session;
        $searchFor = $session->get('searchFor');
        if ( empty($searchFor) ) return;

        $this->stockQuery
            ->andWhere('number_3d LIKE :search OR client LIKE :search OR modeller3d LIKE :search OR model_type LIKE :search OR creator_name LIKE :search OR description LIKE :search OR hashtags LIKE :search')
            ->addParams([':search' => "%$searchFor%"]);
    }

    protected function addByHashtag()
    {
        $session = Yii::$app->session;
        $selectByHashtag = $session->get('selectByHashtag');
        if ( empty($selectByHashtag) ) return;

            $this->stockQuery
                ->andWhere('hashtags LIKE :hashtag')
                ->addParams([':hashtag' => "%$selectByHashtag%"]);
    }

    protected function addFromDate()
    {
        //->andFilterWhere(['between', 'date', $this->start_date, $this->end_date]);
        $session = Yii::$app->session;
        $fromDate = $session->get('selectFromDate');
        if ( empty($fromDate) ) return;

        $this->stockQuery->andFilterWhere(['>=', 'create_date',$fromDate]);
    }
    protected function addToDate()
    {
        $session = Yii::$app->session;
        $toDate = $session->get('selectToDate');
        if ( empty($toDate) ) return;

        $this->stockQuery->andFilterWhere(['<=', 'create_date',$toDate]);
    }

    protected function addOrderBy()
    {
        $session = Yii::$app->session;
        $orderBy = $session->get('selectByOrder');
        if ( empty($orderBy) ) return;
        $ColName = 'date'; // for adding date by default 

        if ($session->get('selectFromDate') || $session->get('selectToDate') )
            $ColName = 'create_date';

        //SORT_DESC
        $this->stockQuery->orderBy([$ColName => $orderBy]);
    }

    public function getStockData() : array
    {
        $session = Yii::$app->session;

        $this->startStockQuery();
       
        $this->addByClient();
        if ( $session->has('searchFor') ) $this->addSearch();
        if ( $session->has('selectByHashtag') ) $this->addByHashtag();
        $this->addFromDate();
        $this->addToDate();
        $this->addOrderBy();

        $this->stockQuery->with(['images']);

        $this->stock = $this->pagination();

        $this->setMainImgforStock();

        return $this->stock;
    }


    protected function setMainImgforStock()
    {
        foreach ( $this->stock as &$model )
        {
            $randomimg = '';
            $found = false;
            foreach ( $model['images'] as $image )
            {
                if ( $image['status'] === 1 ) {
                    $model['mainimage'] = $image['name'];
                    $found = true;
                    break;
                }
            }

            if ( !$found )
            {
                $randomimg = $model['images'][ random_int( 0, (count( $model['images']))-1) ];
                $model['mainimage'] = $randomimg['name'];
            }
        }
    }

    public function pagination() : array
    {
        if ( !$this->stockQuery->exists() ) return [];

        $session = Yii::$app->session;
        $maxPos = $session->get('positionsCount');
        //$maxPos = 5;

        $this->countPos = $this->stockQuery->count();
        $pages = new Pagination(['totalCount' => $this->countPos,'pageSize' => $maxPos]);
        $models = $this->stockQuery->asArray()->offset($pages->offset)->limit($pages->limit)->all();
        $this->pages = $pages;

        return $models;
    }
}
