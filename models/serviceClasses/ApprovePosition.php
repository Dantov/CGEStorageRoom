<?php
namespace app\models\serviceClasses;

use app\models\serviceTables\{Stock,Gems,Materials,Images,D3_files,Users};
use app\models\{Files,User,Common};

use Yii;

/**
 * Some methods for delete models
 */
class ApprovePosition extends SaveModel
{
	
	function __construct( int $modelID = 0 )
	{
		parent::__construct($modelID);
		
	}

    public function publishAllModels() : string
    {
        $stock = Stock::find();
        if ( User::hasPermission('edit_all_models') ) 
        {
            $stock = $stock->andWhere(['model_status'=>0]);
        } elseif ( User::hasPermission('edit_own_models') ) {
            // only self models
            $stock = $stock->andWhere(['model_status'=>0])
                ->andWhere(['creator_id'=>User::getID()]);
        } else {
            return 'false';    
        }

        //$stock = $stock->all();
        $res = [];
        foreach( $stock->each() as $model ) 
        {
            if (empty($model->client)) continue;
            if (empty($model->model_type)) continue;

            $matRows = Materials::find()->where(['pos_id'=>$model->id]);
            if ( !$matRows->exists() ) continue;

            $model->model_status = 1;
            $res[$model->id] = $model->save(false);
        }
        return 'true';
    }

    public function publishModel() : string
    {
        $model = Stock::find()->where(['id'=>$this->modelID]);
        if ( !$model->exists() ) return '';
        $model = $model->one();

        if (empty($model->client)) return '';
        if (empty($model->model_type)) return '';

        $matRows = Materials::find()->where(['pos_id'=>$model->id]);
        if ( !$matRows->exists() ) return '';

        $model->model_status = 1;
        if ( $model->save(false) )
            return 'publish';

        return '';
    }

    public function cloneModel()
    {
        $resp = [
            'result' => false,
            'newid' => false,
        ];
        $stock = Stock::find()->where(['id'=>$this->modelID]);
        if ( !$stock->exists() ) return $resp;
        $stock = $stock->one();

        $newPos = new Stock();
        $newPos->scenario = 'clone';
        $newPos->attributes = $stock->attributes;
        $newPos->id = null;
        $newPos->description = $newPos->description . " CLONED!";
        $newPos->model_status = 0;
        $newPos->date = date('Y-m-d');

        $newPos->isNewRecord = true;

        //debug($newPos->attributes,'newPos',1);
        if ( $newPos->save(false) )
        {
            $newid = $newPos->getPrimaryKey();

            $resp['gems_cloned'] = $this->cloneLinkedTable( 'gems', $newid);
            $resp['mats_cloned'] = $this->cloneLinkedTable( 'mats', $newid);

            if ( $resp['gems_cloned'] && $resp['mats_cloned'] )
                $resp['result'] = true;
            
            $resp['newid'] = $newid;
            Yii::$app->session->setFlash('cloned','Модель клонирована успешно!');
            return $resp;
        }

        $resp['newid'] = -1;
        return $resp;
    }
    protected function cloneLinkedTable( string $tableName, int $newPosID ) : bool
    {
        $table = null;
        if ( $tableName === 'gems' ) $table = Gems::find()->where(['pos_id'=>$this->modelID]);
        if ( $tableName === 'mats' ) $table = Materials::find()->where(['pos_id'=>$this->modelID]);

        if ( !$table->exists() ) return false;

        $results = [];
        foreach ($table->each() as $row) 
        {
            $newTRow = null;
            if ( $tableName === 'gems' ) $newTRow = new Gems();
            if ( $tableName === 'mats' ) $newTRow = new Materials();

            if ( $newTRow === null ) return false;

            $newTRow->scenario = 'clone';
            $newTRow->attributes = $row->attributes;
            $newTRow->id = null;
            $newTRow->pos_id = $newPosID;
            $newTRow->isNewRecord = true;

            $results[] = $newTRow->save(false);
        }

        foreach ($results as $result) {
            if (!$result) return false;
        }
        
        return true;
    }

    public function excludeModel()
    {
        $stock = Stock::find()->select(['id','model_status'])->where(['id'=>$this->modelID])->one();
        $stock->model_status = 0;

        if ( $stock->save(false) )
            return 'exclude';
        return '';
    }
    public function deleteModel()
    {
        $stock = Stock::find()->select(['id','model_status'])->where(['id'=>$this->modelID])->one();
        $stock->model_status = 2;

        if ( $stock->save(false) )
            return 'delete';
        return '';
    }

    public function restorePosition() : string
    {
        $stock = Stock::find()->select(['id','model_status'])->where(['id'=>$this->modelID])->one();
        $stock->model_status = 0;

        if ( $stock->save(false) )
            return 'restored';
        return 'Some error occurred. Model is not restored!';
    }

	/*
     * 
     */
    public function deleteModelFull() : array
    {
        $stock = Stock::find()->select(['id','model_status','client'])
        ->where(['id'=>$this->modelID])
        ->andWhere(['model_status'=>2])
        ->one();
        $result = ['gems'=>false,'materials'=>false,'images'=>false,'data'=>false,'files'=>false];
        $clientName = $stock->client;

        if ( $stock->delete() )
        {
            $result['gems'] = $this->deleteAllFromTable('gems');
            $result['materials'] = $this->deleteAllFromTable('materials');
            $result['images'] = $this->deleteAllFromTable('images');
            $result['data'] = $this->deleteAllFromTable('data');
            $result['filesAccess'] = $this->deleteAllFromTable('userFilesAccess');

            $path = _stockDIR_ . Common::modelPath($clientName,$this->modelID);
            if ( file_exists($path) )
            	$result['files'] = $this->rrmdir( $path );
        }

        return $result; 
    }

    protected function deleteAllFromTable( string $table ) : bool
    {
    	if ( empty($table) ) return false;

    	$count = 0;
    	switch ($table)
    	{
    		case "gems":
    			if ( Gems::find()->where(['pos_id'=>$this->modelID])->exists() )
    				$count = Gems::deleteAll(['pos_id'=>$this->modelID]);
    		break;
    		case "materials":
    			if ( Materials::find()->where(['pos_id'=>$this->modelID])->exists() )
    				$count = Materials::deleteAll(['pos_id'=>$this->modelID]);
    		break;
    		case "images":
    			if ( Images::find()->where(['pos_id'=>$this->modelID])->exists() )
    				$count = Images::deleteAll(['pos_id'=>$this->modelID]);
    		break;
    		case "data":
    			if ( D3_files::find()->where(['pos_id'=>$this->modelID])->exists() )
    				$count = D3_files::deleteAll(['pos_id'=>$this->modelID]);
    		break;
    		case "jb":
    		break;
    		case "userFilesAccess":
    			$user = Users::find()->where(['id'=>User::getID()]);
    			if ( $user->exists() )
    			{
    				$user = $user->select(['files_access'])->one();
    				$fa = json_decode($user->files_access,true);

    				$found = false;
    				foreach($fa??[] as $k => $mid)
    				{
    					if ( $mid == $this->modelID ) {
    						unset($fa[$k]);
    						$found = true;
    					}
    				}
    				if ( $found ) {
    					$user->files_access = json_encode($fa,true);
    					$count = $user->save(false);
    				}
    			}
    		break;
    	}

    	if ( $count ) return true;
    	return false;
    }

    /*
	 * Удаляет папку(вместе с файлами)/файлы по указанному пути
	 */
	protected function rrmdir($src) : bool
	{
	    $dir = opendir($src);
	    while(false !== ( $file = readdir($dir)) ) {
	        if (( $file != '.' ) && ( $file != '..' )) {
	            $full = $src . '/' . $file;
	            if ( is_dir($full) ) {
	                $this->rrmdir($full);
	            }
	            else {
	                unlink($full);
	            }
	        }
	    }
	    closedir($dir);
	    return rmdir($src);
	}
}