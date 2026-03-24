<?php 
namespace app\models;
use app\models\serviceTables\{Service_data, Stock, Mybox, Users};
use app\models\User;
use Yii;

class Common
{
	public static array $projects;
	public static array $roles;
	public static array $userData;
	public static $instance;

	public const int SubstrID_FROM = 16;
	public const int SubstrID_LEN = 14;
	public const int SubstrClient_FROM = 9;
	public const int SubstrClient_LEN = 14;

	public function __construct()
    {
        
    }

    public static function instance()
    {
        if ( self::$instance instanceof self )
            return self::$instance;

        return self::$instance = new self;
    }

    public function getUserDataByID( int $id ) : array
    {
    	if ( isset(self::$userData) ) return self::$userData;
    	
    	if ( $id < 1 || $id > PHP_INT_MAX ) return [];

    	$user = Users::find()
    		->select(['name','lastname','thirdname','fio','fullFio','role','projects','permissions','email','about','access'])
    		->where(['id' => $id]);
    	if ( !$user->exists() ) return [];

    	$user = $user->asArray()->one();

    	$user['role'] = json_decode($user['role'],true);
    	$user['projects'] = json_decode($user['projects'],true);
    	$user['permissions'] = json_decode($user['permissions'],true);

    	return self::$userData = $user;
    }

	public function dateConvert( string $date ) : string
	{
		if ( empty( $date ) ) return '';

		$ex = explode('-',$date);
		return $ex[2] . '.' . $ex[1] . '.' . $ex[0];
	}

	public function convertFileSize( string $sizeByte, string $measure = 'mb', int $precision = 2 ) : string
	{
		$measureTypes = [
            'b' => 1,
            'kb' => 1024,
            'mb' => 1e+6,
            'gb' => 1e+9,
            'tb' => 1e+12,
            'pb' => 1e+15,
        ];
        
        return round( $sizeByte / $measureTypes[$measure], $precision );    
	}

	public function getProjects() : array
	{
		self::$projects = Service_data::find()->where(['tab'=>'project'])->asArray()->orderBy('name')->all();

		if ( User::hasPermission('clientall') )
			return self::$projects;

		if ( User::hasPermission('clientonly') )
		{
			$ids = User::getProjectsID( self::$projects );
			return self::$projects = Service_data::find()
				->where(['tab'=>'project'])
				->andWhere(['in','id',$ids])
				->asArray()
				->orderBy('name')
				->all();
		}
		return [];
	}

	public function getProjectName() : string
	{
		$session = Yii::$app->session;
		$selectedClients = $session->get('SelectByProjects')??[];
		$howManyClients = count($selectedClients);

		if ( empty($howManyClients) ) return 'All';
		if ( $howManyClients > 1 ) return ' ..... ';

		if ( $howManyClients == 1 ) {
			$unhidedName = $selectedClients[ array_key_first($selectedClients) ];
			if ( User::hasPermission('hideclients') ) {

				$allClients = $this->getClients();
				foreach ( $allClients as $clientTmpl ) 
				{
					if ( $clientTmpl['name'] == $unhidedName ){
						return $clientTmpl['secondname'];
					}
				}
			} else {
				return $unhidedName;
			}
		}

		return '';
	}

	public function getAllRoles()
	{
		if ( isset(self::$roles) ) return self::$roles;
		return self::$roles = Service_data::find()->where(['tab'=>'role'])->asArray()->orderBy('name')->all();
	}

	public function getAllHashtags() : array
	{
		return Service_data::find()->where(['tab'=>'hashtag'])->asArray()->orderBy('name')->all();
	}

	public function getAllCategories() : array
	{
		return Service_data::find()->where(['tab'=>'category'])->asArray()->orderBy('name')->all();
	}
	public function getStorageRooms() : array
	{
		return Service_data::find()->where(['tab'=>'storageroom'])->asArray()->orderBy('name')->all();
	}


	public function getNonPublished()
	{
		$stock = Stock::find();
		if ( User::hasPermission('edit_all_models') ) {
			$stock = $stock->andWhere(['item_status' => 0]);
		} elseif ( User::hasPermission('edit_own_models') ) {
			$stock->andWhere(['item_status' => 0])
				  ->andWhere(['creator_id' => User::getID() ]);
		} else {
			return [];
		}
		
		$stock = $stock->with(['images'])->asArray()->all();

		$files = Files::instance();
		$prevSuff = '_prev';
		foreach ( $stock as &$model )
        {
        	if ( empty($model['images']) )
        	{
        		$model['mainimage'] = 'web1.webp';
        		continue;
        	}

        	$modelPath = Common::modelPath($model['project'],$model['id']);

        	$found = false;
            foreach ( $model['images'] as $image )
            {
                if ( (int)$image['status'] === 1 ) {

                	//Image preview check
                	$imgname = $files->getFileName($image['name']);
		            $imgExt = $files->getExtension($image['name']);
		            $previmg = $imgname.$prevSuff.".".$imgExt;
		            $path = _stockDIR_ . $modelPath . "/images/";
		            $fullpath = _stockDIR_ . $modelPath . "/images/".$previmg;
		            $model['path'] = $path;
		            if ( file_exists($fullpath) ) {
		                $model['previmg'] = $previmg;
		            }

                    $model['mainimage'] = $image['name'];
                    $found = true;
                    break;
                }
            }

            if ( !$found )
	        {
	            $randomimg = $model['images'][ array_key_first($model['images']) ];
	            //Image preview check PLEASE!
	            $model['mainimage'] = $randomimg['name'];
	        }
        }

        if ( User::hasPermission('hideclients') )
            $this->hideProjectsName($stock);

        return $stock;
	}

	public function drawEditBtn( int $creatorID ) : bool
    {
   		if (  User::hasPermission('edit_all_models') ) return true;

   		if (  User::hasPermission('edit_own_models') )
   			if ( $creatorID === User::getID() ) return true; 

   		return false;
    }

	public function getBoxStoredModels() : array
	{
		$jb = Mybox::find()->where(['userid'=>User::getID()]);
		if (!$jb->exists()) return [];
        $jb = $jb->all();

        $storeditems = [];
        foreach( $jb as $ordID => $orderData )
        {
        	$om = json_decode($orderData->storeditems,true);
        	$storeditems = array_merge($storeditems, $om);
        }
        return $storeditems;
	}

	public function setIdAsKeys( array &$array )
    {
        foreach ( $array as $key => $element )
        {
            if (!isset($element['id'])) continue;
            $array[$element['id']] = $element;
            unset($array[$key]);
        }
    }

    protected function getClientHash( $client_ID_or_Name ) : string
    {
    	return substr(sha1($client_ID_or_Name), self::SubstrClient_FROM, self::SubstrClient_LEN);
    }
    public static function clientPath( mixed $projectid ) : string
    {
    	$common = Common::instance();
    	if ( is_int($projectid) )
    		return $common->getClientHash($projectid);

    	if ( is_string($projectid) )
    	{
    		$clientName = $projectid;
    		
    		foreach ($common->getProjects() as $project) 
            {
                if ( ($clientName == $project['name']) || $clientName == $project['secondname'] )
                	return $common->getClientHash($project['id']);
            }     
    	}
    	return '';
    }

    public static function modelPath( mixed $projectid, int $modelid ) : string
    {
    	if ( empty($projectid) || empty($modelid) ) return '';
    	$clPath = self::clientPath($projectid);
    	if ( empty($clPath) ) return '';

    	return $clPath ."/". substr(sha1($modelid), self::SubstrID_FROM, self::SubstrID_LEN);
    }

}