<?php
namespace app\models\serviceClasses;

use app\models\serviceTables\{Stock,Service_data};
use app\models\{Files,User};

use Yii;
use yii\helpers\Url;

class AddEdit extends ModelView
{ 
    public array $stock = []; 

    public int $zipSize = 0;
    public int $originSize = 0;
    public array $datafileSizes = [];

	function __construct( int $id )
    {
        parent::__construct($id);
	}

    public function accessControl( string $subtype ) : bool
    {
        switch ($subtype)
        {
            case 'add':
                if ( !User::hasPermission('add_model')) return true;
            break;
            case 'edit':
                if ( User::hasPermission('edit_all_models') ) 
                    return true;
                if ( User::hasPermission('edit_own_models') ) {
                   if (User::getID() === (int)$this->stock['creator_id']) 
                        return true;
                }
            break;
            default:
                return false;
        }

        return false;
    }

    public function getStockData() : array
    {
        $stock = Stock::find()->where(['id' => $this->id]);
        if ( !$stock->exists() )
            return [];
        
        $this->stock = $stock->with(['images'])->asArray()->limit(1)->one();

        //$this->dataFilesPrepare();
        $this->addPreviewImages();
        return $this->stock;
    }

	public function getDataTables()
    {
		$tabs = [
            'category',
            'project',
            'hashtag',
        ];
		$tables = [];

        $service_data = Service_data::find()->asArray()->orderBy('name')->all();

		foreach ( $service_data as $row )
		{
            foreach ( $tabs as $tab )
            {
                if ( $row['tab'] === $tab ) $tables[$tab][] = $row;
            }
		}

        $tables['project'] = $this->getProjects();

		return $tables;
	}
    public function setHashtagsActiv( string $stockHashtags, array &$knownHashtags )
    {
        $stockHashtags = explode('#',$stockHashtags);
        foreach( $knownHashtags as &$knownHashtag )
            $knownHashtag['checked'] = ''; 
 
        foreach( $stockHashtags as $stockHashtag )
        {
            foreach( $knownHashtags as &$knownHashtag )
            {
                if ($stockHashtag === $knownHashtag['name']){
                    $knownHashtag['checked'] = 1; 
                    continue;
                }
            }    
        }
    }

    public function dataFilesPrepare( string $measure = 'mb', int $precision = 2)
    {
        $measureTypes = [
            'b' => 1,
            'kb' => 1024,
            'mb' => 1e+6,
            'gb' => 1e+9,
            'tb' => 1e+12,
            'pb' => 1e+15,
        ];
        
        foreach ( $this->stock['d3_files'] as &$dfile )
        {
            $this->originSize += $dfile['size'];
            $this->zipSize += $dfile['zipsize'];
            $dfile['size'] = round($dfile['size'] / $measureTypes[$measure], $precision ) . $measure;    
        }

        $this->datafileSizes['zip'] = round($this->zipSize / $measureTypes[$measure], $precision ) . $measure; 
        $this->datafileSizes['origin'] = round($this->originSize / $measureTypes[$measure], $precision ) . $measure; 

        //debug($this->stock['d3_files'], 1,1  );
    }

}
