<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\{Main,User,Nom};
use app\models\serviceClasses\{SaveModel,AddEdit,ModelView,JewelStore,UsersAll,Crypt,ApprovePosition};

class SiteController extends GeneralController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays Base Page.
     *
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $session->set('sitepage','main');
		
        $main = new Main();
        $stock = $main->getStockData();
        $pages = $main->pages??null;
        $this->totalCount = $main->countPos;

        $compact = compact(['session','stock','main','pages']);
        return $this->render('index',$compact);
    }

    public function actionView()
    {
        $session = Yii::$app->session;
        $session->set('sitepage','view');

        $request = Yii::$app->request;
        $modelID = (int)$request->get('id');
        if ( $modelID < 0 || $modelID > PHP_INT_MAX )
            Yii::$app->response->redirect(['/site/']);

        $mv = new ModelView( $modelID );
        $model = $mv->getStockData();

        $comp = compact(['model','modelID','mv']);
        return $this->render('view',$comp);
    }

    /**
     * Displays View Page.
     *
     * @return string
     */
    public function actionAdd()
    {   
        $response = Yii::$app->response;
        $session = Yii::$app->session;
        $session->set('sitepage','add-edit');

        //$modelID = Yii::$app->request->get('id');
        $modelID = Yii::$app->request->get('model');
        if ( !$modelID )
        {
             if ( !User::hasPermission('add_model'))
                return $response->redirect(['/site'])->send();

            $sm = new SaveModel();
            $sm->addNewModel();    
            $modelID = $sm->modelID;

            return $response->redirect(['/site/add','model'=>$modelID])->send();
        }

        $addEdit = new AddEdit($modelID);
        $sevData = $addEdit->getDataTables();
        $stockData = $addEdit->getStockData();
        if ( empty($stockData) ) 
            return $response->redirect(['/site'])->send();

        $datafileSizes = $addEdit->datafileSizes;

        if ( !$addEdit->accessControl('edit') )
            return $response->redirect(['/site'])->send();

        $addEdit->setHashtagsActiv($stockData['hashtags'], $sevData['hashtag']);

        $comp = compact(['modelID','sevData','stockData','datafileSizes']);
        return $this->render('add',$comp);
    }
    public function actionEdits()
    {
        Yii::$app->session->setFlash('editModel');
        return $this->actionAdd();
    }
    /**
     *
     * @return string
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        if ( !($request->isAjax && $request->isPost) ) die;
        $v = $request->get('v');
        if ( empty($v) ) die;

        $post = $request->post();
        if ( !$post['modelID'] ) exit(json_encode(false));

        $modelID = (int)$post['modelID'];
        $sm = new SaveModel( $modelID );

        //leave this place if no permission to edit
        if ( !$sm->accessControl() ) exit(json_encode('not enough rights'));
        //Leave this place if model is deleted
        if ( !$sm->isEditable() ) exit(json_encode('not eligible to edit'));

        switch( $v )
        {
            case"inputrow":
                exit(json_encode( $sm->editInputs($post['modelID'], $post) ));
            break;

            //*** FILES ***//
            case"setMainImg":
                exit(json_encode( $sm->setMainImg( $post['imgRowID'] )));
            break;
            case"dellFile":
                exit(json_encode( $sm->dellFile( $post )));
            break;
            case"pushfiles":
                exit(json_encode( $sm->addNewFile( $modelID )));
            break;

            //*** Tables ***//
            case"linktable":
                exit(json_encode( $sm->addNewLinkedRow( $post['tableName'] )));
            break;
            case"dellrow":
                exit(json_encode( $sm->dellRowLinked($post) ));
            break;
            case"duplicate":
                exit(json_encode( $sm->duplicateRowLinked($post) ));
            break;
            case"editLinkedRow":
                exit(json_encode( $sm->editLinkedRow($post) ));
            break;

            //*** Hashtags ***//
            case"hashtagdell":
                exit(json_encode( $sm->deleteHashtags($modelID,$post) ));
            break;
            case"hashtagcheck":
                exit(json_encode( $sm->hashtagByClick($modelID,$post) ));
            break;
            case"hashtagByText":
                exit(json_encode( $sm->hashtagByText($modelID,$post) ));
            break;

        }
        
    }

    public function actionApproverPosition()
    {
        $request = Yii::$app->request;
        if ( !($request->isAjax && $request->isPost) ) die;

        $v = $request->get('v');
        if ( empty($v) ) die;

        $post = $request->post();
        if ( !isset($post['modelID']) || empty($post['modelID']) )
            exit(json_encode('wrong id'));

        $modelID = (int)$post['modelID'];
        $apprPos = new ApprovePosition( $modelID );

        switch ($v) 
        {
            case 'deletefull':
                //leave this place if no permission to edit
                if ( !User::isAdmin() ) 
                    exit(json_encode('not enough rights'));
                exit(json_encode( $apprPos->deleteModelFull() ));
            break;
            case 'restore':
                if ( !User::isAdmin() ) 
                    exit(json_encode('not enough rights'));
                exit(json_encode( $apprPos->restorePosition() ));
            break;
            case 'publish':
                if ( !$apprPos->isEditable() ) 
                    exit(json_encode('not eligible to edit'));
                exit(json_encode( $apprPos->publishModel() ));
            break;
            case 'publishall':
                exit(json_encode( $apprPos->publishAllModels() ));
            break;
            case 'exclude':
                if ( !$apprPos->isEditable() ) 
                    exit(json_encode('not eligible to edit'));
                exit(json_encode( $apprPos->excludeModel() ));
            break;
            case 'deletemodel':
                if ( !$apprPos->isEditable() ) 
                    exit(json_encode('not eligible to edit'));
                exit(json_encode( $apprPos->deleteModel() ));
            break;
            case 'clone':
                if ( !$apprPos->isEditable() ) 
                    exit(json_encode('not eligible to edit'));
                exit(json_encode( $apprPos->cloneModel() ));
            break;
        }
        
    }

    public function actionJewel()
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        $proceed = ($request->isAjax && $request->isPost);
        $box = (string)$request->get('box');
        $jewelbox = new JewelStore( $request->post() );

        switch($box)
        {
            case "add":
                if ( !$jewelbox->accessControl() ) 
                    exit(json_encode("false 123"));

                if ( !$proceed ) exit(json_encode(false));
                exit(json_encode($jewelbox->add()));
            break;
            case "show":
                if ( !$jewelbox->accessControl() ) 
                    $response->redirect(['/site/error/','message'=>"forbidden"])->send();

                $allOrders = $jewelbox->getAllOrders( User::getID() );
                $comp = compact(['allOrders']);
                return $this->render('jewelbox',$comp);
            break;
            case "showorders":
                if ( !User::isAdmin() ) 
                    $response->redirect(['/site/error/','message'=>"forbidden"])->send();

                $allOrders = $jewelbox->getAllOrders();
                $comp = compact(['allOrders']);
                return $this->render('jewelboxorders',$comp);
            break;
            case "setmodelprice":
                if ( !User::isAdmin() || !$proceed ) exit(json_encode(false));

                exit(json_encode( $jewelbox->setModelPrice() ));
            break;
            case "openmodel":
                if ( !User::isAdmin() || !$proceed ) exit(json_encode(false));
                exit(json_encode( $jewelbox->openModelFiles('one') ));
            break;
            case "openallmodels":
                if ( !User::isAdmin() || !$proceed ) exit(json_encode(false));

                exit(json_encode( $jewelbox->openModelFiles('all') ));
            break;
            case "edit":
                if ( !$proceed ) exit(json_encode(false));
                if ( !$jewelbox->accessControl() ) exit(json_encode(false));

                exit(json_encode( $jewelbox->edit() ));
            break;
            case "remove":
                if ( !$jewelbox->accessControl() ) 
                    $response->redirect(['/site/error/','message'=>"forbidden"])->send();

                $jewelbox->remove($request->get('id'),$request->get('orderid')); 
                $response->redirect(['/site/jewel/','box'=>'show'])->send();
            break;
            case "sendorder":
                if ( !$jewelbox->accessControl() ) 
                    $response->redirect(['/site/error/','message'=>"forbidden"])->send();

                if ( !$jewelbox->sendOrder( $request->get('orderid') ) ) 
                    $response->redirect(['/site/error/','message'=>"При обоаботке заказа возникла ошибка!"])->send();

                $response->redirect(['/site/jewel/','box'=>'show'])->send();
            break;
            case "removeorder":
                if ( !$jewelbox->accessControl() ) 
                    $response->redirect(['/site/error/','message'=>"forbidden"])->send();

                $jewelbox->removeOrder($request->get('orderid')); 
                $response->redirect(['/site/jewel/','box'=>'show'])->send();
            break;
        }

        $response->redirect(['site/'])->send();
    }

    /**
     * Displays Nomenclature page.
     *
     * @return Response|string
     */
    public function actionNomenclature()
    {
        if (!User::hasPermission(35)) 
            Yii::$app->response->redirect('/site')->send();

        $nom = new Nom();
        $modelTypes = $nom->getModelTypes();
        $projects = $nom->getProjects('sizes');

        $comp = compact(['modelTypes','projects']);
        return $this->render('nomenclature',$comp);
    }

    /**
     * Displays user profile page.
     *
     * @return Response|string
     */
    public function actionProfile()
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;
        if ( !User::hasPermission(70) ) 
            $response->redirect('/site')->send();

        $edit = (string)$request->get('edit');
        if ($request->isAjax && $request->isPost)
        {
            $post = $request->post();
            if (!isset($post['uid']) ) exit(json_encode( false ));
            $uid = (int)Crypt::strDecode($post['uid']);
            $users = new UsersAll($uid);
            if (!$users) exit(json_encode( false ));

            
            switch ( $edit )
            {
                case "text":        
                    exit(json_encode( $users->editInput($post) ));
                break;
                case "picture":
                    exit(json_encode( $users->uploadPicture() ));
                break;
            }
            exit(json_encode( false ));
        }

        if ( $edit === 'dellavatar') {
            (new UsersAll(User::getID()))->deletePicture();
            $response->redirect('/site/profile/')->send();
        }   

        return $this->render('profile');
    }
    
    /**
     * Displays user options page.
     *
     * @return Response|string
     */
    public function actionOptions()
    {
        if (!User::hasPermission(69)) 
            Yii::$app->response->redirect('/site')->send();

        $data = "data";

        $comp = compact(['data']);
        return $this->render('options',$comp);
    }
    /**
     * Displays user statistic page.
     *
     * @return Response|string
     */
    public function actionStatistic()
    {
        if (!User::hasPermission(36)) 
            Yii::$app->response->redirect('/site/error?id=frule')->send();




        return $this->render('statistic');
    }

    public function sendEmail()
    {
        $data = $this->post;
        //debug($data,'$data',1);

        $name = Validator::validateString($data['name']);
        $email = Validator::validateString($data['email']);
        $message = Validator::validateString($data['message']);
        $subject  = Validator::validateString($data['subject']);

        if ( empty($name) || empty($email) || empty($message) || empty($subject) ) return null;

        //$to  = "AlmTade s.r.o. <info@almtradesro.com>";
        
        $c_message = " 
        <html>
            <body>
                <p>
                   Новое сообщение от: <strong>$name</strong><br>
                </p>
                <p>$message</p>
            </body>
        </html>";

        $headers  = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: $name <$email>";

        
        if ( mail($to, $subject, $c_message, $headers) ) return 1;
            
        return null;
    }

    /**
     * Displays user statistic page.
     *
     * @return Response|string
     */
    public function actionError()
    {
        $get = Yii::$app->request->get();
        
        $comp = compact(['get']);
        return $this->render('error',$comp);
    }






    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
