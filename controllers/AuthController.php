<?php

namespace app\controllers;

use Yii;
use app\models\serviceTables\Users;
use app\models\Auth;
use yii\web\Controller;

class AuthController extends Controller
{
    public function beforeAction($action)
    {
        $this->layout = 'modern_login';
        return parent::beforeAction($action);
    }

    /*
     * Форма логин
     */
    public function actionIndex()
    {
        $this->view->title = "Login";
        return $this->render('login');
    }

    public function actionLogin()
    {
        $session = Yii::$app->session;
        $response = Yii::$app->response;
        $session->set('sitepage','login');
        /*
        if ( $session->get('access') === 1 ) 
            return Yii::$app->response->redirect('/site')->send();
        */

        if ( !Yii::$app->request->isPost ) 
            return $response->redirect('/auth')->send();
        
        $auth = new Auth();
        $proceed = false;
        if ( $auth->submited && $auth->haveLogin && $auth->havePass ){
            $proceed = $auth->proceed();
        }
        
        if ( $proceed )
                $auth->authorize();

        $response->redirect('/site')->send();
    }

    public function actionLogout()
    {
        (new Auth())->exit();
        Yii::$app->response->redirect('/auth');
    }
}