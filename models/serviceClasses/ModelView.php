<?php
namespace app\models\serviceClasses;

use app\models\serviceTables\Stock;
use app\models\{Common,Files,User};

use Yii;
use yii\helpers\Url;

class ModelView extends Common
{
    public $id;
    public $number_3d;
	public $row;

    function __construct( $id = 0 )
    {
        $session = Yii::$app->session;

        if ( isset($id) ) $this->id = $id;

        parent::__construct();
    }

	public function getStockData()
    {
        $this->row = Stock::find()
            ->with(['images','materials','gems','d3_files'])
            ->where(['=','id',$this->id])
            ->asArray()
            ->limit(1)
            ->one();

		$this->number_3d = $this->row['number_3d'];
        $this->setMainImg();
        $this->addPreviewImages();
        $this->setSizesRange();
        $this->setHashtags();
        $this->setDataFiles();
        $this->setJewelStoredModels();

        $this->setClientID();
        $this->row['isEditBtn'] = $this->drawEditBtn( $this->row['creator_id'] );


		return $this->row;
	}

    protected function addPreviewImages()
    {
        $files = Files::instance();
        $prevSuff = '_prev';
        $row = [];
        if ( isset($this->row) )
        {
            $row = &$this->row;
        } elseif ( isset($this->stock) ) {
            $row = &$this->stock;
        } else {
            return;
        }
   
        foreach ( $row['images'] as &$image )
        {
            $imgname = $files->getFileName($image['name']);
            $imgExt = $files->getExtension($image['name']);
            $previmg = $imgname.$prevSuff.".".$imgExt;

            $modelPath = Common::modelPath($row['client'],$this->id);

            $path = _stockDIR_ . $modelPath . "/images/";
            $fullpath = _stockDIR_ . $modelPath . "/images/".$previmg;
            $image['path'] = $path;
            if ( file_exists($fullpath) ) {
                $image['previmg'] = $previmg;
            } else {
                if ( !file_exists($path) ) continue;
                if (ImageConverter::makePrev( $path, $image['name'] ) )
                    $image['previmg'] = ImageConverter::getLastImgPrevName();
            }
        }
    }

    protected function setMainImg()
    {
        if ( empty($this->row['images']) )
        {
            $this->row['mainimage'] = '';
            $this->row['mainimageID'] = 0;
            return;
        }
        $this->setIdAsKeys($this->row['images']);
        $found = false;
        foreach ( $this->row['images'] as $image )
        {
            if ( (int)$image['status'] === 1 )
            {
                $this->row['mainimage'] = $image['name'];
                $this->row['mainimageID'] = $image['id'];
                $found = true;
                break;
            }
        }
        if ( !$found )
        {

            //$min = 0;
            //$max = (count($this->row['images']))-1;
            //$i = $max ? random_int($min, $max) : array_key_first($this->row['images']);
            $i = array_key_first($this->row['images']);
            $randomimg = $this->row['images'][ $i ];
            //$randomimg = $this->row['images'][ random_int( 0, (count( $this->row['images']))-1) ];

            $this->row['mainimage'] = $randomimg['name'];
            $this->row['mainimageID'] = $randomimg['id'];
        }
    }
    protected function setSizesRange()
    {
        $this->row['size_range'] = explode('-',$this->row['size_range']);
    }
    protected function setHashtags()
    {
        $hashtagsC = ['success','info','warning','primary','secondary','danger','dark'];

        $hashtags = explode('#', $this->row['hashtags']);
        foreach ( $hashtags as $key => $hashtag )
            if ( empty($hashtag) ) unset($hashtags[$key]);
        
        $this->row['hashtags'] = $hashtags;
        $this->row['hashtags_colors'] = $hashtagsC;
    }

    protected function setDataFiles()
    {  
        $this->row['overal_size'] = 0;
        $this->row['overal_zipsize'] = 0;
        foreach ( $this->row['d3_files'] as &$dfile )
        {
            $this->row['overal_size'] += $dfile['size'];
            $this->row['overal_zipsize'] += $dfile['zipsize'];
            
            $dfile['size'] = $this->convertFileSize($dfile['size']);    
            $dfile['zipsize'] = $this->convertFileSize($dfile['zipsize']); 
        }

        $this->row['overal_size'] = $this->convertFileSize($this->row['overal_size']);   
        $this->row['overal_zipsize'] = $this->convertFileSize($this->row['overal_zipsize']);   
    }

    protected function setClientID()
    {
        $allClients = $this->getClients();
        
        foreach ( $allClients as $clientTmpl )
        {
            if ( $this->row['client'] == $clientTmpl['name'] )
            {
                if ( User::hasPermission('hideclients') )
                    $this->row['client'] = $clientTmpl['secondname'];
                
                $this->row['clientID'] = $clientTmpl['id'];
                break;
            }
        }
    }

    protected function setJewelStoredModels()
    {
        $jsm = $this->getJewelStoredModels();

        $this->row['stored'] = false;
        foreach ( $jsm as $storedmodel )
        {
            if ( $this->row['id'] == $storedmodel['id'] ) {
                $this->row['stored'] = true;
                break;
            }
        }
    }

}