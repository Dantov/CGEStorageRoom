<?php
namespace app\controllers;

use Yii;
use yii\filters\{AccessControl,VerbFilter};
use yii\web\Response;
use app\models\User;
use app\models\serviceClasses\{UsersAll,Crypt};
use app\models\serviceTables\{Service_data,Permissions};

class UsersController extends GeneralController
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
    public function actionShowAll()
    {
        $users = new UsersAll();

        $all = $users->getAllUsers();
        if ( !$users->accessControl() ) 
            throw new \Exception('you have no rights!',500);

        $compact = compact(['all','users']);
        return $this->render('showall',$compact);
    }

    public function actionAdd()
    {
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        $session->set('sitepage','adduser');
        $users = new UsersAll();

        if ( !$users->accessControl() ) 
            throw new \Exception('you have no rights!',500);

        if ( $request->isPost )
        {
            $post = $request->post();
            if ( $uid = $users->addNewUser($post) )
            {
                $struid = Crypt::strEncode($uid);
                $session->setFlash('allgood','You are added new user successfully!');
                return $response->redirect(['/users/edit', 'id' => $struid]);     
            } else {
                $session->setFlash('saveErrors','Error was happening while saving data!');
            }
        }

        $clients = $users->getBasicData('clients');
        $allroles = $users->getBasicData('roles');
        $permissions = $users->getBasicData('perm');
        
        $compact = compact(['clients','allroles','permissions']);
        return $this->render('add',$compact);
    }

    /**
     * Shows User page data to edit
     * */
    public function actionEdit( string $id )
    {
        $session = Yii::$app->session;
        $session->set('sitepage','edituser');
    
        $id = (int)Crypt::strDecode($id);
        $users = new UsersAll($id);

        if ( !$users->accessControl() ) 
            throw new \Exception('you have no rights!',500);

        $single = $users->user;
        $clients = $users->getClients();
        $allroles = $users->getRoles();
        $permissions = $users->permissionsApplyed();
        $uPermissions = $users->hisPermissions();

        $compact = compact(['single','clients','allroles','permissions','uPermissions']);
        return $this->render('edit',$compact);
    }

    public function actionEditUser()
    {
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        $post = $request->post();
        $id = Crypt::strDecode($post['uid']);

        $users = new UsersAll($id);

        // for update user permittion and clients
        if ( $request->isAjax && $request->isPost )
        {
            if (!$users->accessControl()) exit( json_encode("no permission to edit") ); 
            if ( !isset($post['permid']) ) exit( json_encode(false) );
            $permid = (int)$post['permid'];

            if ( $request->get('applyright') )
                exit( json_encode($users->applyRight($permid)));
            if ( $request->get('removeright') )
                exit( json_encode($users->removeRight($permid)));
        }

        // for rest user data
        if ( $request->isPost )
        {
            if (!$users->accessControl()) 
                throw new \Exception("no permission to edit",500);

            $struid = Crypt::strEncode($id);
            if ( $users->saveUserData( $post ) )
            {
                $session->setFlash('allgood','User data saved success!');
            } else {
                $session->setFlash('saveErrors','Error was happening while saving data!');    
            }
            
            return $response->redirect(['/users/edit', 'id' => $struid]); 
        }
    }
    public function actionDelete()
    {
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        $post = $request->post();
        $id = (int)Crypt::strDecode( $request->get('id') );

        $users = new UsersAll($id);
        if (!$users->accessControl()) 
                throw new \Exception("no permission to edit",500);

        if ( $id )
        {
            if ($users->deleteUser($id)){
                $session->setFlash('dellgood','User was deleted!');
            } else {
                $session->setFlash('dellError','Error was happening while deleting user!');    
            }

            return $response->redirect(['/users/show-all']); 
        }

        return $response->redirect(['/']); 
    }

}
