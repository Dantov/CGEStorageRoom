<?php
namespace app\models\serviceClasses;

use app\models\serviceTables\{Stock,Service_data,Mybox,Users};
use app\models\{Common,Files,User,Validator};

use Yii;
use yii\helpers\Url;

class MyStore extends Common
{ 
    protected int $modelID;
    protected string $modelComment;
    protected int $orderID;
    protected string $price;
    protected string $room;
    protected string $shelf;
    protected int $filesAccess;

	public function __construct( array $post )
    {
        $v = new Validator();

        if ( isset($post['modelID']) ) {
            $id = (int)$post['modelID'];

            //if ( $id < 1 || $id > PHP_INT_MAX ) return false;
            $this->modelID = $id;
        }

        if ( isset($post['comment']) )
            $this->modelComment = trim( $v->sanitarizePost('comment') );

        if ( isset($post['orderid']) ) {
            $orderid = (int)$post['orderid'];
            if ( $orderid < 1 || $orderid > PHP_INT_MAX ) return false;
            $this->orderID = $orderid;
        }

        if ( isset($post['price']) ) {
            $this->price = trim( $v->sanitarizePost('price') );
        }

        if ( isset($post['access']) ) {
            $access = (int)$post['access'];
            //if ( $access < 0 || $access > 1 ) return false;
            $this->filesAccess = $access;
        }

        if ( isset($post['room']) ) {
            $this->room = strip_tags(trim($post['room']));
        }
        if ( isset($post['shelf']) ) {
            $this->shelf = strip_tags(trim($post['shelf']));
        }

        parent::__construct();
	}

    public static function getModelsCount() : int
    {
        $jb = Mybox::find()->where(['userid'=>User::getID()])->andWhere(['status'=>0]);
        if ($jb->exists()) {
            $jb =$jb->one();
            return count(json_decode($jb->storeditems,true)??[]);
        } 
        return 0;
    }
    public static function getOrdersCount() : int
    {
        $jb = Mybox::find()->where(['status'=>1])->orWhere(['status'=>0]);
        if ( $jb->exists() ) 
        {
            return $jb->count();
        } 
        return 0;
    }
    public function getOrderStatus( int $id ) : int
    {
        if ( $id < 1 || $id > PHP_INT_MAX ) return false;
        $jb = Mybox::find()->select(['id','status'])->where(['userid'=>User::getID()])->andWhere(['id'=>$id]);
        if (!$jb->exists()) return false;
        $jb = $jb->one();

        return $jb->status;
    }
    public static function getOrderID( int $modelID = 0 ) : int
    {
        $jb = Mybox::find()->select(['id'])->where(['userid'=>User::getID()]);
        if (!$jb->exists()) return false;
        $jb = $jb->one();



        return $jb->id;
    }

    public function addItem() : bool
    {
        $itemData = Stock::find()->where(['id'=>$this->modelID]);
        if (!$itemData->exists()) false;
        $itemData = $itemData->one();

        // Check if
        if ( $itemData->item_quantity < 1 ) return false;

        // If all good reserv this item
        //$itemData->reserv_user_id = User::getID();
        //$itemData->reserv_user_name = User::getFIO();
        $itemData->item_quantity = $itemData->item_quantity-1;
        $reserved = $itemData->save(false);
        if ( !$reserved ) return false;

        $mybox = Mybox::find()->where(['userid'=>User::getID()])->andWhere(['status'=>0]);
        $boxItems = [];
        if ($mybox->exists())
        {
            $mybox = $mybox->one();
            $boxItems = json_decode($mybox->storeditems,true)??[];
        } else {
            $mybox = new Mybox();    
        }

        $boxItem = [
            'id' => $this->modelID,
            'comment' => $this->modelComment,
            'username' => User::getFIO(),
            'room' => $itemData->storageroom,
            'shelf' => $itemData->shelfnum,
            'grabbingdate' => date('Y-m-d'),
            'price' => '',
        ];
        $boxItems[] = $boxItem;
        
        $mybox->storeditems = json_encode($boxItems,true);
        $mybox->userid = User::getID();
        $mybox->lastdate = date('Y-m-d');

        if ( $mybox->save(false) ) {
            return true;
        } else {
            // Unreserve if something wrong
            $itemData->reserv_user_id = null;
            $itemData->reserv_user_name = null;
            $itemData->item_quantity++;
            $itemData->save(false);
        }

        return false;
    }

    public static function hasItemInMyBox( int $itemID ) : bool
    {
        $mybox = Mybox::find()->where(['userid'=>User::getID()]);
        $boxItems = [];
        if (!$mybox->exists()) return false;
        
        $mybox = $mybox->one();
        $boxItems = json_decode($mybox->storeditems,true)??[];
        
        foreach ($boxItems as $boxedItem) 
        {
            if ( (int)$boxedItem['id'] === $itemID ) return true;
        }

        return false;
    }

    public function getAllOrders( int $userID = 0 ) : array
    {
        $jb = Mybox::find();
        if ( $userID ) {
           $jb->where(['userid'=>User::getID()]); 
        } else {
            // For admin we don't show not formed orders
            $jb->where(['<>','status',0]); 
        }

        if ( !$jb->exists() ) return [];

        $jb = $jb->asArray()->all();

        $this->setIdAsKeys($jb);

        foreach( $jb as &$order ) {
            //debug(json_decode($order['storeditems'],true),'storeditems',1);
            $order['storeditems'] = $this->proceedStoredModels(json_decode($order['storeditems'],true)??[]);

            //debug($order['storeditems'],'storeditems');
            $this->setIdAsKeys($order['storeditems']);

            //debug($order['storeditems'],'setIdAsKeys',1);

            $order['userdata'] = $this->getUserDataByID($order['userid']);
            $order['lastdate'] = $this->dateConvert($order['lastdate']);
        }

        //debug($jb,'$jb',1); 
        return $jb;
    }

    protected function proceedStoredModels( array $storedmodels )
    {
        $ids = [];
        foreach( $storedmodels as $sm )
            $ids[] = $sm['id'];

        $stock = Stock::find()->where(['in','id',$ids]);
        if (!$stock->exists()) return [];
        $stock = $stock->with('images')->asArray()->all();

        foreach( $stock as &$model ) {
            foreach( $storedmodels as $sm ) {
                if ( $model['id'] === $sm['id'] ){
                    $model['comment'] = $sm['comment'];
                    $model['storeprice'] = $sm['price'];
                    $model['grabbingdate'] = $sm['grabbingdate'];
                    //$model['access'] = $sm['access'];// round($model['model_cost'] / 2); //
                }
            }
            foreach( $model['images'] as $img ) {
                if ( (int)$img['status'] === 1 ){

                    $model['mainimage'] = "stock/".Common::modelPath($model['project'],$model['id'])."/images/".$img['name'];
                    break;
                }
            }
        }
        //if ( User::hasPermission('hideclients') ) $this->hideClientsName($stock);
        return $stock;
    }
    /*
    protected function hideClientsName( array &$stock )
    {
        $allClients = $this->getClients();
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
    */
    /*
     * OLD
     */
    /*
    public function getStoredModels() : array
    {
        $jb = Mybox::find()->where(['userid'=>User::getID()]);
        $storedmodels = [];
        if (!$jb->exists()) return [];
        
        $jb = $jb->all();
        
        $resp = [
            'storeditems' => [],
            'statuses' => [],
        ];
        foreach( $jb as $num => $orders )
        {
            $storedmodels = json_decode($orders->storeditems,true)??[];
            $resp['storeditems'][$orders->id] = $this->proceedStoredModels($storedmodels);
            $resp['statuses'][$orders->id] = $orders->status;
        }
        return $resp;
    }
    */

    public function edit()
    {
        $jb = Mybox::find()->where(['userid'=>User::getID()])->andWhere(['id'=>$this->orderID]);//andWhere(['status'=>0]);
        if (!$jb->exists()) return false;
        $jb = $jb->one();
        $storedmodels = json_decode($jb->storeditems,true)??[];

        $flag = false;
        foreach( $storedmodels as $key => &$storedmodel ) {
            if ( (int)$storedmodel['id'] === $this->modelID ) {
                $storedmodel['comment'] = $this->modelComment;
                $flag = true;
                break;
            }
        }

        if ($flag) {
            $jb->storeditems = json_encode($storedmodels,true);
            $jb->lastdate = date('Y-m-d');
            
            return $jb->save(false);    
        }
        return false;
    }

    public function returnItem() : bool
    {
        //if ( $id < 1 || $id > PHP_INT_MAX ) return false;
        //if ( $orderid < 1 || $orderid > PHP_INT_MAX ) return false;

        $box = Mybox::find()->where(['userid'=>User::getID()])->andWhere(['id'=>$this->orderID]);
        if ( !$box->exists() ) return false;
        $box = $box->one();
        $storeditems = json_decode($box->storeditems,true)??[];

        $flag = false;
        foreach( $storeditems as $key => $storeditem ) {
            if ( (int)$storeditem['id'] === $this->modelID ) {
                unset($storeditems[$key]);
                $flag = true;
                break;
            }
        }

        if ($flag) {
            $box->storeditems = json_encode($storeditems,true);
            $box->lastdate = date('Y-m-d');
            $returned = $box->save(false);
        }

        if ($returned)
        {
            $itemData = Stock::find()->where(['id'=>$this->modelID]);
            if (!$itemData->exists()) return false;
            $itemData = $itemData->one();

            // If all good unreserv this item
            $itemData->storageroom = $this->room;
            $itemData->shelfnum = $this->shelf;
            
            //$itemData->reserv_user_id = null;
            //$itemData->reserv_user_name = null;
            $itemData->item_quantity++;
            return $itemData->save(false);
        }

        return false;
    }

    public function removeOrder( int $orderid )
    {
        if ( $orderid < 1 || $orderid > PHP_INT_MAX ) return false;
        $jb = Mybox::find()->where(['userid'=>User::getID()])->andWhere(['id'=>$orderid]);
        if (!$jb->exists()) return false;
        $jb = $jb->one();

        if ($jb->delete())
        {
            /*
            $sended = Yii::$app->mailer->compose()
            //->setFrom('insidemail@powered-jewelry-base.com')
            ->setTo('vady365@yahoo.com')
            ->setSubject('PJ3DB - Заказ УДАЛЕН!')
            ->setTextBody('Заказ № ' . $jb->id . ' от ' . User::getFIO() . ' УДАЛЕН!')
            ->setHtmlBody('Заказ № <i>' . $jb->id . '</i> от ' .'<b>'.User::getFIO().'</b> УДАЛЕН!')
            ->send();
            */
        }
    }

    public function openModelFiles( string $condition = 'one' ) : bool
    {
        // Jewel Box Part
        $jb = Mybox::find()->where(['id'=>$this->orderID]);
        if ( !$jb->exists() ) return false;
        $jb = $jb->one();
        $userID = $jb->userid;
        $storedmodels = json_decode($jb->storeditems,true) ?? [];
        $found = false;
        $allIDs = [];
        foreach ($storedmodels as &$modeldata) 
        {
            if ( $condition === 'one' )
            {
                if ( (int)$modeldata['id'] === $this->modelID ) {
                    $modeldata['access'] = 1;
                    $found = true;
                    break;
                }
            } elseif ( $condition === 'all' ) {
                $modeldata['access'] = 1;
                $allIDs[] = $modeldata['id'];
                $found = true;
            }
        }
        unset($modeldata); // super need it here to not rewrite var on next foreach
        if ( !$found ) return false;

        // Check if all models are open to set complete to order
        if ( $condition === 'all' ) $jb->status = 2;

        if ( $condition === 'one' ) {
            $flagStatus2 = true;
            foreach ($storedmodels as $modeldata) {
                if ( (int)$modeldata['access'] === 0 ) {
                    $flagStatus2 = false;
                    break;
                }
            }   
            if ( $flagStatus2 ) $jb->status = 2;
        }

        $jb->storeditems = json_encode($storedmodels,true);
        $jb->save(false);    

        // User Part 
        $userData = Users::find()->select(['id','files_access'])->where(['id'=>$userID]);
        if ( !$userData->exists() ) return false;
        $userData = $userData->one();
        $fa = json_decode($userData->files_access,true) ?? [];
        if ( $condition === 'all' ) {
            foreach ( $allIDs as $singleID )
            {
                if ( !in_array($singleID, $fa) )
                    $fa[] = $singleID;
            }
        } elseif ( $condition === 'one' ) {
            if ( !in_array($this->modelID, $fa) )
                    $fa[] = $this->modelID;
        }
        $userData->files_access = json_encode($fa,true);
        return $userData->save(false);
    }

    public function setModelPrice()
    {
        $jb = Mybox::find()->where(['id'=>$this->orderID]);
        if ( !$jb->exists() ) return false;
        $jb = $jb->one();

        $storedmodels = json_decode($jb->storeditems,true) ?? [];
        $flag = false;
        foreach( $storedmodels as $key => &$storedmodel ) {
            if ( (int)$storedmodel['id'] === $this->modelID ) {
                $storedmodel['price'] = $this->price;
                $flag = true;
                break;
            }
        }
        if ($flag) {
            $jb->storeditems = json_encode($storedmodels,true);
            $jb->lastdate = date('Y-m-d');
            return $jb->save(false);    
        }
        return false;
    }

    public function accessControl() : bool
    {
        if ( User::hasPermission('mybox')) return true;
        return false;
    }
}
