<?php
namespace app\controllers;
//use app\models\serviceClasses\SaveModel;
use Yii;

class SearchController extends GeneralController
{

    public function actionSet()
    {
        $request = Yii::$app->request;
        if ( $request->isAjax && $request->isPost )
        {
            $searchFor = mb_strtolower( trim(strip_tags($request->post('search_for'))) );
            $session = Yii::$app->session;
            $session->set('searchFor', $searchFor);
            exit(json_encode(true));
        }
        exit(json_encode(false));
    }
    protected function purge()
    {
        $session = Yii::$app->session;

        $session->set('SelectByProjects',[]);

        $session->set('SelectByProject','All');
        $session->set('searchFor', '');
        $session->set('selectByHashtag', '');
        $session->set('selectByCategory', '');

        $session->set('selectFromDate','');
        $session->set('selectToDate','');
        //$session->set('selectByOrder', SORT_ASC);

        $session->set('SelectByNonPub', '');
        $session->set('SelectByDeleted', '');
    }
    public function actionPurge()
    {
        $request = Yii::$app->request;
        if ( $request->isAjax && $request->isPost )
        {
            if ( (int)$request->post('clean') !== 1 ) exit(json_encode(false));
            $this->purge();
            exit(json_encode(true));
        }

        Yii::$app->response->redirect(['/site'])->send();
    }

    public function actionSelect()
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;
        $session = Yii::$app->session; 

        $value = $request->get('v');
        switch( $request->get('by') )
        {
            case "project":
                if ( $value ) {
                    $this->SelectByProject( (int)$value );
                    $this->SelectByProjects( (int)$value );
                }  
            break;
            case "hashtag":
                if ( $value )
                    $this->SelectByHashtags( $value );
            break;
            case "order":
                if ( $value ) 
                    $this->orderBy($value);
            break;
            case "category":
                if ( $value ) 
                    $this->SelectBy($value,'selectByCategory', $this->categories);
            break;
            
            case "nonpub":
                $session->set('SelectByDeleted', '');
                $session->set('SelectByNonPub', 1);
            break;
            case "deleted":
                // Only nonPublished or Deleted can be displyed at one time
                $session->set('SelectByNonPub', '');
                $session->set('SelectByDeleted', 1);
            break;
            case "purgedate":
                $this->purgeDate();
            break;
            case "purgeall":
                $this->purge();
            break;
        }

        $response->redirect(['/site'])->send();
    }
    
    protected function SelectBy( string $needle, string $sessName, array $heap )
    {
        $session = Yii::$app->session;
        if ( (int)$needle === 123 )
        {
            $session->set($sessName, '');
            return;
        }
        foreach ( $heap as $row )
        {
            if ( $row['name'] === $needle )
            {
                $session->set($sessName, $row['name']);
                break;
            }
        }
    }

    protected function SelectByProjects( int $project )
    {
        $session = Yii::$app->session;
        if ( (int)$project === 11 )
        {
            $session->set('SelectByProjects', []);
            return;
        }

        function setProjects( $newPrjName, &$session )
        {
            $stPrjs = $session->get('SelectByProjects')??[];
            $found = false;
            foreach ( $stPrjs as $key => $selectedPrjName )
            {
                if ( $selectedPrjName == $newPrjName )
                {
                    //remove cl here
                    unset($stPrjs[$key]);
                    $found = true;
                    break;
                }
            }
            if ( !$found ) {
                //add new client name here
                $stPrjs[] = $newPrjName;
            }
            return $stPrjs;
        }

        foreach ( $this->projects as $singlePrj )
        {
            if ( (int)$singlePrj['id'] === $project )
            {
                $session->set('SelectByProjects', setProjects($singlePrj['name'],$session) );
                break;
            }
        }
    }

    protected function SelectByProject( int $project )
    {
        $session = Yii::$app->session;
        if ( (int)$project === 11 )
        {
            $session->set('SelectByProject', 'All');
            return;
        }
        foreach ( $this->projects as $singleProject )
        {
            if ( $singleProject['id'] === $project )
            {
                $session->set('SelectByProject', $singleProject['name']);
                break;
            }
        }
    }
   
    protected function SelectByHashtags( string $hashtag )
    {
        $session = Yii::$app->session;
        if ( (int)$hashtag === 123 )
        {
            $session->set('selectByHashtags', []);
            return;
        }

        $hashtags = $session->get('selectByHashtags');
        foreach ( $this->hashtags as $singleHashtag )
        {
            if ( $singleHashtag['name'] === $hashtag )
            {
                if ( in_array($hashtag, $hashtags) ) {
                    //Delete Htag if already in query
                    foreach ( $hashtags as $key => $htag ) {
                        if ( $htag == $hashtag ) {
                            unset($hashtags[$key]);
                            break;
                        }
                    }
                    
                } else {
                    // Add Htag for query
                    $hashtags[] = $hashtag;
                }
                break;
            }
        }
        $session->set('selectByHashtags', $hashtags);
    }

    protected function orderBy( string $order )
    {
        $session = Yii::$app->session;
        switch ( $order )
        {
            case 'ASC':
                $session->set('selectByOrder', SORT_ASC);
            break;
            case 'DESC':
                $session->set('selectByOrder', SORT_DESC);
            break;
        }
    }

    public function actionFromDate()
    {
        $request = Yii::$app->request;
        if ( $request->isAjax && $request->isPost )
        {
            $session = Yii::$app->session;

            $date = $request->post('date');
            if (empty( $date )) exit(json_encode(false));

            $session->set('selectFromDate', $date);
            exit(json_encode(true));
        }
        exit(json_encode(false));
    }
    public function actionToDate()
    {
        $request = Yii::$app->request;
        if ( $request->isAjax && $request->isPost )
        {
            $session = Yii::$app->session;

            $date = $request->post('date');
            if (empty( $date )) exit(json_encode(false));

            $session->set('selectToDate', $date);
            exit(json_encode(true));
        }
        exit(json_encode(false));
    }
    protected function purgeDate()
    {
        $session = Yii::$app->session;
        if (!empty($session->get('selectToDate')))
                $session->set('selectToDate','');

        if (!empty($session->get('selectFromDate')))
                $session->set('selectFromDate','');
    }
    public function actionPositionsCount()
    {
        $request = Yii::$app->request;
        $get = (int)$request->get('v');
        if ( $get < 1 || $get > PHP_INT_MAX ) 
            return Yii::$app->response->redirect(['/site'])->send();

        $session = Yii::$app->session;
        switch ( $get )
        {
            case 27:
                $session->set('positionsCount', 27);
            break;
            case 54:
                $session->set('positionsCount', 54);
            break;
            case 108:
                $session->set('positionsCount', 108);
            break;
            case 216:
                $session->set('positionsCount', 216);
            break;
            default:
                $session->set('positionsCount', 27);
            break;
        }
        Yii::$app->response->redirect(['/site'])->send();
    }

    public function actionControlSize()
    {
        $request = Yii::$app->request;
        if ( $request->isAjax && $request->isPost )
        {
            $session = Yii::$app->session;
            $size = $request->post('size');
            if ( $size < 6 ) $size = 6;
            if ( $size > 24 ) $size = 24;
            $session->set('tilesControlSize', $size);
            exit(json_encode(['size'=>$size, 'done'=>true]));
        }
        exit(json_encode(false));
    }

}
