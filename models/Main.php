<?php
namespace app\models;

use app\models\serviceTables\Stock;
use app\models\{User,Files};
use app\models\serviceClasses\{ImageConverter};

use Yii;
use yii\helpers\Url;
use yii\db\ActiveQuery;
use yii\data\Pagination;

class Main extends Common
{ 
    public array $stock = []; 
    public int $countPos = 0;
    public Pagination $pages;

    protected ActiveQuery $stockQuery;

    protected function startStockQuery()
    {
        $this->stockQuery = Stock::find();
    }

    protected function addByProjects()
    {
        $session = Yii::$app->session;
        $chosenClients = $session->get('SelectByProjects');

        if ( User::hasPermission('clientonly') && !User::hasPermission('clientall') ) 
        {
            $selfClients = [];
            foreach ( self::$projects as $cl )
                $selfClients[] = $cl['name'];

            if ( !empty($chosenClients) )
                $selfClients = $chosenClients;

            return $this->stockQuery->andWhere(['in', 'project', $selfClients]);
        }
        
        if ( !empty($chosenClients) )
            return $this->stockQuery->andWhere(['in', 'project', $chosenClients]);
    }
    
    protected function addSearch()
    {
        $session = Yii::$app->session;
        if ( !$session->has('searchFor') ) return;
        
        $searchFor = $session->get('searchFor');
        if ( empty($searchFor) ) return;

        $this->stockQuery
            ->andWhere('item_name LIKE :search OR project LIKE :search OR item_category LIKE :search OR description LIKE :search OR hashtags LIKE :search')
            ->addParams([':search' => "%$searchFor%"]);
    }
    
    protected function addNonPublishedAndDeleted()
    {
        $session = Yii::$app->session;
        $byNonPub = $session->get('SelectByNonPub');
        $byDeleted = $session->get('SelectByDeleted');

        // Normal Mode
        if ( empty($byNonPub) && empty($byDeleted) ) 
        {
            $this->stockQuery->andWhere(['item_status' => 1]);
            return;
        }

        //Non Published Mode
        if ( !empty($byNonPub) ) 
            $this->stockQuery->andWhere(['item_status' => 0]);

        //Deleted Mode
        if ( !empty($byDeleted) ) 
            $this->stockQuery->andWhere(['item_status' => 2]); 
    }

    protected function addByCategory()
    {
        $session = Yii::$app->session;
        $selectByCategory = $session->get('selectByCategory');
        if ( empty($selectByCategory) ) return;

            $this->stockQuery
                ->andWhere('item_category LIKE :cat')
                ->addParams([':cat' => "%$selectByCategory%"]);
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
    protected function addByHashtags()
    {
        $session = Yii::$app->session;
        $hashtags = $session->get('selectByHashtags');
        if ( empty($hashtags) ) return;
        $str = '';
        foreach ( $hashtags as $htag )
        {
            $str.= "hashtags LIKE '%$htag%' OR ";
        }
        $str = trim($str,' OR ');
        $this->stockQuery->andWhere($str);
                //->andWhere('hashtags LIKE :hashtag');
                //->addParams([':hashtag' => "%$htag%"]);
        //debug($str,'$$str',1);
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
        $ColName = 'stock.id'; // for adding date by default 

        if ($session->get('selectFromDate') || $session->get('selectToDate') )
            $ColName = 'create_date';

        //SORT_DESC
        $this->stockQuery->orderBy([$ColName => $orderBy]);
    }

    public function getStockData() : array
    {
        $session = Yii::$app->session;

        $this->startStockQuery();
       
        $this->addByProjects();
        $this->addSearch();
        $this->addByHashtags();
        $this->addByCategory();
        $this->addFromDate();
        $this->addToDate();
        $this->addOrderBy();
    
        $this->addNonPublishedAndDeleted();
        
        $this->stockQuery->with(['images']);
        
        $this->stock = $this->pagination();

        $this->setMainImgforStock();
        if ( User::hasPermission('hideclients') )
            $this->hideClientsName($this->stock);

        if ( User::hasPermission('jewelbox') )
            $this->setJewelStoredModels();



        foreach ($this->stock as &$model)
            $model['isEditBtn'] = $this->drawEditBtn( $model['creator_id'] );

        return $this->stock;
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

    protected function setMainImgforStock()
    {
        foreach ( $this->stock as &$model )
        {
            $randomimg['name'] = '';
            $found = false;

            if ( !count($model['images']) ) {
                $model['mainimage'] = '';
                $model['mainimgprev'] = '';
                continue;
            }

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
                if ( count($model['images'] )) {
                    $min = 0;
                    $max = (count($model['images']))-1;
                    $i = $max ? random_int( $min, $max ) : 0;
                    $randomimg = $model['images'][ $i ];
                }
                $model['mainimage'] = $randomimg['name'];
            }

            if ( $prevImgName = $this->addPreviewImages( $model['mainimage'], $model['id'], $model['project'] ) )
                $model['mainimgprev'] = $prevImgName;
        }
    }

    protected function addPreviewImages( $mainimgname, $id, $client ) : string
    {
        $files = Files::instance();
        $prevSuff = '_prev';
        
        $imgname = $files->getFileName($mainimgname);
        $imgExt = $files->getExtension($mainimgname);
        $previmg = $imgname.$prevSuff.".".$imgExt;

        $modelPath = Common::modelPath($client, $id);

        $path = _stockDIR_ . $modelPath . "/images/";
        if ( file_exists($path.$previmg) ) {
            return $previmg;
        } else {
            if ( !file_exists($path) ) return "";
            if (ImageConverter::makePrev( $path, $mainimgname ) )
                return ImageConverter::getLastImgPrevName();
        }
        return "";
    }

    protected function hideProjectsName( array &$stock )
    {
        $allClients = $this->getProjects();
        foreach ( $stock as &$model )
        {
            foreach ( $allClients as $clientTmpl )
            {
                if ( $model['project'] == $clientTmpl['name'] ){
                    $model['project'] = $clientTmpl['secondname'];
                    break;
                }
            }
        }
    }

    

    protected function setJewelStoredModels()
    {
        $jsm = $this->getJewelStoredModels();

        foreach ( $this->stock as &$model )
        {
            $model['stored'] = false;
            foreach ( $jsm as $storedmodel )
            {
                if ( $model['id'] == $storedmodel['id'] ) {
                    $model['stored'] = true;
                    break;
                }
            }
        }
    }
}
