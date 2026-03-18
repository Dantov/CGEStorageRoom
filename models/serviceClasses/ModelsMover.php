<?php
namespace app\models\serviceClasses;

use app\models\serviceTables\{Stock,Service_data,Gems,Materials,Images,D3_files};
use app\models\{UploadImages,Common,Files,Validator,User};
use app\models\serviceClasses\ImageConverter;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ModelsMover extends Common
{

    public int $modelID;

    public function __construct(  )
    {
        parent::__construct();
    }



    public function getStockData() : array
    {

        $stock = Stock::find()->select(['id','client']);//->where(['>=','id', 159 ]);
        if ( !$stock->exists() )
            return [];

        $this->stock = $stock->asArray()->all();

        $clients = $this->getClients();

        foreach ($this->stock as $key => &$model) 
        {
            $model['modelid_hash'] = substr(sha1($model['id']), Common::SubstrID_FROM, Common::SubstrID_LEN);
            foreach ($clients as $client) 
            {
                if ( $model['client'] == $client['name'] )
                {
                    $model['client_id'] = $client['id'];
                    $model['clientid_hash'] = substr(sha1($client['id']), Common::SubstrClient_FROM, Common::SubstrClient_LEN);
                }
            }       
        }
        return $this->stock;
    }

    public function checkSHAID( int $id ) : array
    {
        $modelidhash = substr(sha1($id), Common::SubstrID_FROM, Common::SubstrID_LEN);
        foreach ($this->stock as $model) 
        {
            if ( $model['modelid_hash'] === $modelidhash ) return $model;
        }

        return [];
    }

    public function moveModelFiles( string $oldPath, string $newPath ) : bool
    {
        if ( file_exists($oldPath) )
        {
            if ( !file_exists($newPath) ) 
                mkdir($newPath, 0777, true);

            $files = Files::instance();
            $files->xcopy($oldPath,$newPath);
            $files->rrmdir($oldPath);

            return true;
        }

        return false;
    }

    public function moveModel( array $modeltomove, Files $files ) : bool
    {
        $oldPath = _stockDIR_ . $modeltomove['id']; 
        if ( file_exists($oldPath) )
        {
            $newClientPath = _stockDIR_ . $modeltomove['clientid_hash'];
            if ( !file_exists($newClientPath) )
                mkdir($newClientPath, 0777, true);

            //$newpath = _stockDIR_ . $modeltomove['modelid_hash'];
            $newpath =  $newClientPath ."/". $modeltomove['modelid_hash'];
            $this->xcopy($oldPath,$newpath);
            $files->rrmdir($oldPath);
        }

        return true;
    }

    protected function xcopy($src, $dest) 
    {
        foreach (scandir($src) as $file) {
            if (!is_readable($src . '/' . $file)) continue;
            if (is_dir($src .'/' . $file) && ($file != '.') && ($file != '..') ) {
                if (!is_dir($dest . '/' . $file))
                {
                    mkdir($dest . '/' . $file, 0777, true);
                }
                $this->xcopy($src . '/' . $file, $dest . '/' . $file);
            } else if (($file != '.') && ($file != '..')) {
                copy($src . '/' . $file, $dest . '/' . $file);
            }
        }
    }
}