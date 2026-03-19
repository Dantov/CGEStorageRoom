<?php
namespace app\models\serviceClasses;

use app\models\serviceTables\{Stock,Service_data,Gems,Materials,Images,D3_files};
use app\models\{UploadImages,Common,Files,Validator,User};
use app\models\serviceClasses\ImageConverter;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Обрабатывает данные из формы сохранения модели
 */
class SaveModel extends Common
{

    public int $modelID;

    public function __construct( int $modelID = 0 )
    {
        if ( $modelID < 0 || $modelID > PHP_INT_MAX )
            throw new \Exception("Wrong id!", 510);

        if ($modelID) $this->modelID = $modelID;
        parent::__construct();
    }

    public function addNewModel():bool
    {
        $session = Yii::$app->session;
        $date = date("Y-m-d");

        $stock = new Stock();
        $stock->item_category = '';
        $stock->item_size = '';
        $stock->item_price = '';
        $stock->description = '';
        $stock->hashtags = '';
        $stock->date = $date;
        $stock->create_date = $date;
        $stock->creator_id = User::getID();

        $res = $stock->save(false);
        $this->modelID = $stock->getPrimaryKey();

        return $res;
    }

    public function editInputs( int $id, array $post ):bool
    {
        $value = trim(strip_tags($post['value']));
        if (empty($value)) return false;

        $name = $post['name'];
        if ( $name === 'project' )
            return $this->editProjectField( $value );
        
        $stock  = Stock::find()->select(['id',$name])->where(['id' => $id])->one();
        $stock->$name = $value;
        return $stock->save(false);
    }
    protected function editProjectField( string $projectName ) : bool
    {
        $oldPath = $this->getModelPath();
        $newPath = Common::modelPath($projectName, $this->modelID);

        $res = ['saved'=>false,'modelsMoved'=>false];
        $stock = Stock::find()->select(['id','project'])->where(['id' => $this->modelID])->one();
        $stock->project = $projectName;
        $res['saved'] = $stock->save(false);
        
        if ( empty($oldPath) && $res['saved'] ) return true;

        if ( !empty($newPath) ) 
        {
            $mm = new ModelsMover();
            return $mm->moveModelFiles( _stockDIR_.$oldPath, _stockDIR_.$newPath );
        }

        return false;
    }

    public function hashtagByClick( int $id, array $post ) : bool
    {
        $name = $post['name'];
        $value = trim(strip_tags($post['value']));
        if (empty($value)) return false;
        
        $stock  = Stock::find()->select(['id',$name])->where(['id' => $id])->one();

        $hashtags = $stock->$name;
        $stock->$name = $hashtags.'#'.$value;  

        return $stock->save(false);
    }

    public function hashtagByText( int $id, array $post ) : bool
    {
        $name = $post['name'];
        $value = trim(strip_tags($post['value']));

        if (empty($value)) return false;

        $knownHashtag = Service_data::find()->where(['tab'=>'hashtag'])->asArray()->all();

        $newFlag = false;

        foreach( $knownHashtag as $knownHashtag )
        {
            if ( $knownHashtag['name'] === $value ) {
                $newFlag = true;
                break;
            };   
        } 

        if (!$newFlag) {
            $sd = new Service_data();
            $sd->name = $value;
            $sd->tab = 'hashtag';
            $sd->save(false);
        }

        $stock  = Stock::find()->select(['id',$name])->where(['id' => $id])->one();

        $hashtags = $stock->$name;
        $stock->$name = $hashtags.'#'.$value;  

        return $stock->save(false);
    }

    public function deleteHashtags( int $id, array $post ) : bool
    {
        $name = $post['name'];
        $value = $post['value'];
        $value = trim(strip_tags($post['value']));
        if (empty($value)) return false;

        $stock  = Stock::find()->select(['id',$name])->where(['id' => $id])->one();
        $hashtags = explode('#',$stock->$name);
        
        foreach( $hashtags as $key => $hashtag )
        {
            if ( $hashtag === '' ) unset($hashtags[$key]);   
            if ( $hashtag === $value ) unset($hashtags[$key]);
        } 

        $newHashtags = '';
        foreach( $hashtags as $hashtag ) 
            $newHashtags .= '#'.$hashtag;
        $stock->hashtags = $newHashtags;  
        
        return $stock->save(false);
    }

    public function editLinkedRow( array $post ) : int
    {
        //debug($post,1,1);

        $table = null;
        $rowID = $post['id'];
        $name = $post['name'];
        $tableName = $post['tableName'];

        $value = trim(strip_tags($post['value']));
        if (empty($value)) return false;

        switch ( $tableName )
        {
            case 'tableMats':
                $table = Materials::find()->select(['id',$name])->where(['id' => $rowID])->one();
            break;

            case 'tableGems':
                $table = Gems::find()->select(['id',$name])->where(['id' => $rowID])->one();
            break;
        }

        if ($table) {
            $table->$name = $value;
            return $table->save(false);
        }

        return false;
    }

    public function addNewLinkedRow( $tableName ) : int
    {
        //debug($tableName,1,1);

        $table = null;

        switch ( $tableName )
        {
            case 'materials':
                $table = new Materials();
                $table->part = '';
                $table->metal = '';
                $table->probe = '';
                $table->color = '';
            break;

            case 'gems':
                $table = new Gems();
                $table->name = '';
                $table->cut = '';
                $table->value = 1;
                $table->size = '';
                $table->color = '';
            break;
        }

        if ( $table ) {
            $table->pos_id = $this->modelID;
            $table->save(false);
            return $table->getPrimaryKey();
        }

        return false;
    }

    public function duplicateRowLinked( array $post )
    {
        //debug($post,1,1);
        $table = null;
        $duplRow = [];

        $rowID = $post['rowID'];
        $pos_id = $post['modelID'];
        $tableName = $post['tableName'];

        switch ( $tableName )
        {
            case 'tableMats':
                $duplRow = Materials::find()
                ->select(['part','metal','probe','color','pos_id'])
                ->where(['id' => $rowID])
                ->andWhere(['pos_id' => $pos_id])
                ->asArray()
                ->limit(1)
                ->one();
                $table = new Materials();
                $table->part = $duplRow['part'];
                $table->metal = $duplRow['metal'];
                $table->probe = $duplRow['probe'];
                $table->color = $duplRow['color'];
                $table->pos_id = $duplRow['pos_id'];
            break;
            case 'tableGems':
                $duplRow = Gems::find()
                ->select(['name','cut','value','size','color','pos_id'])
                ->where(['id' => $rowID])
                ->andWhere(['pos_id' => $pos_id])
                ->asArray()
                ->limit(1)
                ->one();
                $table = new Gems();
                $table->name = $duplRow['name'];
                $table->cut = $duplRow['cut'];
                $table->value = $duplRow['value'];
                $table->size = $duplRow['size'];
                $table->color = $duplRow['color'];
                $table->pos_id = $duplRow['pos_id'];
            break;
        }

        if ( $table )
        {
            $table->save(false);
            return $table->getPrimaryKey();
        }

        return false;
    }

    public function dellRowLinked( array $post )
    {
        //debug($post,1,1);
        $table = null;
        $rowID = (int)$post['rowID'];
        $pos_id = (int)$post['modelID'];
        $tableName = $post['tableName'];

        switch ( $tableName )
        {
            case 'tableMats':
                $table = Materials::find()
                ->where(['id' => $rowID])
                ->andWhere(['pos_id' => $pos_id])
                ->limit(1)
                ->one();
            break;
            case 'tableGems':
                $table = Gems::find()
                ->where(['id' => $rowID])
                ->andWhere(['pos_id' => $pos_id])
                ->limit(1)
                ->one();
            break;
        }

        if ( $table )
            return $table->delete();

        return false;
    }

    public function setMainImg(int $imgRowID) : bool
    {
        $imageToUnset = Images::find()->where(['status'=>1])->andWhere(['pos_id' => $this->modelID])->one();
        if ( $imageToUnset ) 
        {
            $imageToUnset->status = 0;
            $imageToUnset->save(false);
        }
        
        $imageToSet = Images::findOne($imgRowID);
        return $imageToSet->updateCounters(['status' => 1]);
    }

    //добавляем новые картинки, и вносим данные в Images
    public function addNewFile( $modelID ) : array
    {
        $files = Files::instance();
        //debug( $files->get(),1,1);

        if ( $files->has('UploadImage') )
            return $this->uploadImageFile($files);

        if ( $files->has('Upload3DFile') )
        {
            $res = $this->checkOveralFilesize( $files->get('Upload3DFile') );
            if ( $res === true ) {
                return $this->uploadDataFile($files);
            } else {
                return $res;
            }
        }
        return [];
    }
    /*
     GET Project NAME FOR PATH
    */
    protected function getModelPath() : string
    {
        $stock = Stock::find()->select('project')->where(['id'=>$this->modelID]);
        if ( !$stock->exists() ) return '';
        $stock = $stock->one();
        if ( empty($stock->project) ) return '';
        return Common::modelPath($stock->project,$this->modelID);
    }

    protected function uploadImageFile( Files $files ) : array
    {
        $modelPath = $this->getModelPath();
        if ( empty($modelPath) ) 
            return ['id'=>0,'upload'=>false,'type'=>'picture','txt'=>'Wrong destination! Project name is empty.'];

        $uplImg = $files->get('UploadImage');
        $newImgName = '';
        $images = new Images();

        $images->name = $newImgName = $this->modelID."-".randomStringChars( 20, 'en', 'symbols').'.'.$files->getExtension($uplImg['name']);
        $images->status = 0;
        $images->size = $uplImg['size'];
        $images->pos_id = $this->modelID;

        $images->save(false);
        $imgID = $images->getPrimaryKey();

        $destPath = _stockDIR_ .$modelPath.'/images/'; 

        if ( !file_exists($destPath) ) 
            mkdir($destPath, 0777, true);
        $res = false;
        $res = $files->upload($uplImg['tmp_name'], $destPath.$newImgName, ['png','gif','jpg','jpeg','webp']);
        if ($res)
        {
            /** оптимизация размера файла */
            ImageConverter::optimizeUpload($destPath.$newImgName);

            /** Сднлаем превью загруженного файла */
            //ImageConverter::makePrev($destPath.$newImgName);
            ImageConverter::makePrev($destPath,$newImgName);
        }

        return ['id'=>$imgID,'upload'=>$res,'type'=>'picture'];
    }
    protected function checkOveralFilesize( array $fileData ) : mixed
    {
        $sql  = "SELECT SUM(size) as s, SUM(zipsize) as z FROM d3_files WHERE pos_id={$this->modelID}";
        $data = D3_files::findBySql($sql)->asArray()->one();
        
        $haveSize = (int)$data['s'];
        $currentFileSize = (int)$fileData['size'];
        $total = $currentFileSize + $haveSize;
        $overallAllowedSize = 40100000; // 40.1 mb

        if ( $total > $overallAllowedSize )
            return ['id'=>0,'upload'=>false,'type'=>'data','txt'=>'Your files got too much size. Max allowed is 40mb total.'];
        
        return true;      
    }
    protected function uploadDataFile( Files $files ) : array
    {
        $modelPath = $this->getModelPath();
        if ( empty($modelPath) ) 
            return ['id'=>0,'upload'=>false,'type'=>'data','txt'=>'wrong destination! Client name is empty.'];

        $uplFile = $files->get('Upload3DFile');
        $fileExtension = $files->getExtension($uplFile['name']);
        //$newFileName = $this->modelID."_".randomStringChars( 10, 'en', 'symbols').'.'.$fileExtension;
        $vl = new Validator();
        $newFileName = $vl->sanitizeFileName( $files->getFileName( $uplFile['name'] ) );
        $newFileName = $newFileName ."_".randomStringChars( 7, 'en', 'symbols').'.'.$fileExtension;

        $destPath = _stockDIR_ . $modelPath .'/3dfiles/'; 
        if ( !file_exists($destPath) ) 
            mkdir($destPath, 0777, true);
        $uploadRes = false;
        //debug($uplFile['tmp_name']." - ". $destPath.$newFileName, 1,1 );
        $uploadRes = $files->upload($uplFile['tmp_name'], $destPath.$newFileName, ['3dm','stl','mgx','ai','dxf','obj','zip','rar']);

        //$rowID = 0;
        if ( $uploadRes )
        {
            if ( $fileExtension == 'zip' || $fileExtension == 'rar') {
               return $this->uploadArchive($newFileName, $uplFile, $fileExtension, $uploadRes);
            } else {
               return $this->uploadNonArchive($files, $destPath, $newFileName, $fileExtension, $uplFile, $uploadRes);
            }
        }
        return ['id'=>0,'upload'=>false,'type'=>'data','txt'=>'Some error occurred while saving file!'];
    }
    protected function uploadArchive( $newFileName, $uplFile, $fileExtension, $uploadRes)
    {
        // DB Record
        $d3_files = new D3_files();
        $d3_files->name    = $newFileName;
        $d3_files->zipname = $newFileName;
        $d3_files->type    = $fileExtension;
        $d3_files->size    = $uplFile['size'];
        $d3_files->zipsize = $uplFile['size'];
        $d3_files->pos_id  = $this->modelID;
        $d3_files->save(false);
        $rowID = 0;
        $rowID = $d3_files->getPrimaryKey();

        return ['id'=>$rowID,'upload'=>$uploadRes,'type'=>'data'];
    }
    protected function uploadNonArchive( Files $files, $destPath, $newFileName, $fileExtension, $uplFile, $uploadRes)
    {
        $newFileNameZip = $this->modelID."_zip_".randomStringChars( 10, 'en', 'symbols');
        $zipArch = $this->openZip( $destPath , $newFileNameZip );
        $zipArch['inst']->addFile( $destPath.$newFileName, $newFileName );
        $this->closeZip($zipArch['inst']);
        $files->delete($destPath.$newFileName);
        
        // DB Record
        $d3_files = new D3_files();
        $d3_files->name    = $newFileName;
        $d3_files->zipname = $zipArch['zipName'];
        $d3_files->type    = $fileExtension;
        $d3_files->size    = $uplFile['size'];
        $d3_files->zipsize = $files->getFileSize($destPath.$zipArch['zipName']);
        $d3_files->pos_id  = $this->modelID;
        $d3_files->save(false);
        $rowID = 0;
        $rowID = $d3_files->getPrimaryKey();

        return ['id'=>$rowID,'upload'=>$uploadRes,'type'=>'data'];
    }

    public function dellFile( array $post ) : array
    {
        $res = ['file'=>false,'row'=>false,'type'=>''];

        $modelPath = $this->getModelPath();
        if ( empty($modelPath) ) return $res;

        switch( $post['fileType'] )
        {
            case "picture":
                $res['type'] = "picture";
                $rowID = $post['rowID'];
                $images = Images::find()->where(['id'=>$rowID])->limit(1)->one();

                $destPath = _stockDIR_ . $modelPath .'/images/' . $images->name; 

                $files = Files::instance();
                // Need to dell Prev too!!!
                $fname = $files->getFileName($images->name);
                $fext = $files->getExtension($images->name);
                $destPathPrev = _stockDIR_ . $modelPath .'/images/' . $fname.'_prev.'.$fext; 
                
                if ( $res['file'] = $files->delete($destPath) )
                {
                    $files->delete($destPathPrev);
                    $res['row'] = (bool)$images->delete();
                }
                
            break;
            case "data":
                $res['type'] = "data";
                $rowID = $post['rowID'];
                $d3files = D3_files::find()->where(['id'=>$rowID])->limit(1)->one();

                $destPath = _stockDIR_ . $modelPath .'/3dfiles/' . $d3files->zipname; 

                $files = Files::instance();
                
                if ( $res['file'] = $files->delete($destPath) )
                $res['row'] = (bool)$d3files->delete();
            break;
        }
        
        return $res;
    }

    public function openZip( string $zip_path, string $zip_name ) : array
    {
        $zip = new \ZipArchive();
        $zip_name = $zip_name . ".zip";
        $zip->open($zip_path.$zip_name, \ZIPARCHIVE::CREATE);

        return ['inst'=>$zip, 'zipName' => $zip_name];
    }
    public function closeZip( \ZipArchive $zip ) : bool
    {
        if ( method_exists($zip,'close') )
        {
            $zip->close();
            return true;
        }
        return false;
    }

    public function accessControl() : bool
    {
        if ( User::hasPermission('edit_all_models') ) 
            return true;

        if ( User::hasPermission('edit_own_models') ) 
        {
            $stock = Stock::find()
                ->select(['id','creator_id','item_status'])
                ->where(['id'=>$this->modelID])
                ->andWhere([ 'creator_id' => User::getID() ]);

           if ( $stock->exists() ) return true;
        }
        return false;
    }

    public function isEditable() : bool
    {
        $stock = Stock::find()
            ->select(['id','item_status'])
            ->where(['id'=>$this->modelID])
            ->andWhere(['item_status'=>2]);

        if ( $stock->exists() ) return false;

        return true;
    }
}