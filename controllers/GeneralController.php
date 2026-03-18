<?php

namespace app\controllers;

use app\models\serviceTables\Users;
use app\models\Main;
use app\models\Auth;
use app\models\User;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

class GeneralController extends Controller
{
    public $layout = 'modernize';

    public bool $isMobile = false;
    public bool $isDesktop = false;
    public string $jsCONSTANTS = "";

    /*
     * текущий ир юзвера
     */
    public $IP_visiter = '';

    /*
     * выборка юзверов из БД
     */
    protected $rootDir;
    protected $stockDir;

    public array $user;
    public array $clients;
    public array $hashtags;
    public array $modelTypes;
    public array $modelMaterials;
    public array $nonPublished;
    public string $clientHidedName;
    public string $clientName;
    public int $totalCount;
    
    public $status_arr = [];
    public $labels_arr = [];
    public $img_arr = [];

    public function beforeAction($action)
    {
        $this->view->title = "CGE::Storage Room";
        //HTTP_REFERER
        if ( !$this->accessControl() ) 
		  return $this->redirect(['/auth'])->send();
		
        $this->IP_visiter = $_SERVER['SERVER_ADDR'];

        $this->isMobile  = $this->isMobileCheck();
        $this->isDesktop = !$this->isMobile;
        
        $isMb = $this->isMobile ? 'true' : 'false';
        $this->jsCONSTANTS = <<<JS
            const _IS_MOBILE_  = {$isMb};
            const _IS_DESKTOP_ = !_IS_MOBILE_;
JS;
        try {
            $m = new Main();
            $this->clients = $m->getClients();
            $this->hashtags = $m->getAllHashtags();
            $this->modelTypes = $m->getAllModelTypes();
            $this->modelMaterials = $m->getAllMaterials();
            $this->nonPublished = $m->getNonPublished();
            $this->clientName = $m->getClientName();
        } catch (e) {
            return $this->redirect(['/auth'])->send();
        }
        
        return parent::beforeAction($action);
    }

    protected function accessControl() : bool
    {
        $auth = new Auth();
        return $auth->accessControl();
    }

    public function isMobileCheck()
    {
        $ua = '';
        if (filter_has_var(INPUT_SERVER,'HTTP_USER_AGENT'))
            $ua = filter_input(INPUT_SERVER,'HTTP_USER_AGENT');
        
        //$ua = $_SERVER['HTTP_USER_AGENT']??" ";
        return stripos($ua,'mobile') !== false ? true : false;
    }

}