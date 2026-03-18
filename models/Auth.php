<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\Cookie;
use app\models\serviceTables\Users;
//use models\Validator;

class Auth extends Common
{
    public string $action = '';
    public Validator $validator;

    public bool $submited;
    public bool $haveLogin;
    public bool $havePass;
    public bool $haveMeme;

    protected array $users = [];
    protected array $user = [];

    private const int APP_VERSION_ACCESS = 305;

    public function __construct()
    {
        $this->submited  = filter_has_var(INPUT_POST, 'submit');
        $this->haveLogin = filter_has_var(INPUT_POST, 'login');
        $this->havePass  = filter_has_var(INPUT_POST, 'pass');
        $this->haveMeme  = filter_has_var(INPUT_POST, 'memeMe');

        //$user = Users::findBySql("SELECT * FROM users WHERE login=:login",[':login'=>$userData['login']])->asArray()->limit(1)->one();
        //debug($user, '$user', 1);

        $this->validator = new Validator();

        parent::__construct();
    }

    public function getAppVersionAccess() : int
    {
        return self::APP_VERSION_ACCESS;
    }

    public function proceed() : bool
    {
        $session = Yii::$app->session;
        if ($session->get('sitepage') !== 'login' ) return false;
        $this->users = Users::find()->asArray()->all();

        if ( !count($this->users) ) throw new \Exception ("Can't get users!", 500);


        if ( $this->checkLogin() )
        {
            if ( $this->checkPassword() ) {
                return true;
            }
        } else {
            $session->setFlash('wrongLog', ' не верен!');
        }
        return false;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function checkLogin() : bool
    {
        $login = $this->validator->ValidateLogin('login');
        foreach ( $this->users as $user )
        {
            if ( isset($user['login']) )
            {
                //hash_equals();
                //Крайне важно задавать строку с пользовательскими данными вторым аргументом, а не первым.
                if ( hash_equals($user['login'], $login) )
                {
                    $this->user = $user;
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $password
     * @return bool
     * @throws \Exception
     */
    protected function checkPassword() : bool
    {
        if ( !isset($this->user['pass']) ) return false;

        $pass = $this->validator->validatePassword('pass');
        if ( password_verify($pass, $this->user['pass']) )
        {
            return true;
        } else {
             Yii::$app->session->setFlash('wrongPass', ' не верен!');
        }

        //$hash = Yii::$app->getSecurity()->generatePasswordHash($password);
        /*
        if (Yii::$app->getSecurity()->validatePassword($password, $hash)) {
            // всё хорошо, пользователь может войти
        } else {
            // неправильный пароль
        }
        */

        return false;
    }

    /**
     * @param $userRow
     * @throws \Exception
     */
    public function authorize()
    {   
        $session = Yii::$app->session;
        if ($session->get('sitepage') !== 'login' ) return false;

        $this->setSessionVariables($session);

        // если установлен флажок на "запомнить меня" пишем все в печеньки
        if ( $this->haveMeme )
        {
            $this->setCookieVariables($session);
        }
        
        //debug($session->getAll(),'sess');
        //debug(Cookies::getAll(),'cook',1);
    }

    protected function setSessionVariables($session)
    {
        // Base access, user can enter database with this number 
        $session->set('access', 1);

        $user['id'] = $this->user['id'];
        $user['fio'] = $this->user['fio'];
        $user['fullFio'] = $this->user['fullFio'];
        $user['access'] = $this->user['access'];
        $session->set('user', $user);

        $session->set('AppVersionAccess',$this->getAppVersionAccess() );

        $session->set('positionsCount',27);
        $session->set('SelectByClients',[]);
        $session->set('SelectByClient','All');
        $session->set('searchFor', '');
        $session->set('selectByHashtag', '');
        $session->set('selectByHashtags', []);
        $session->set('selectByModelType', '');
        
        $session->set('selectFromDate','');
        $session->set('selectToDate','');
        $session->set('selectByOrder', SORT_DESC);//SORT_ASC
        $session->set('tilesControlSize', 12);

        $session->set('selectByMatColor', '');
        $session->set('selectByMatMetal', '');
        $session->set('selectByMatProbe', '');

        $session->set('SelectByNonPub', '');
        $session->set('SelectByDeleted', '');
    }

    protected function setCookieVariables($session)
    {
        $cookies = Yii::$app->response->cookies;
        $expired = time() + (3600 * 24 * 30);

        $cookies->add(new Cookie([
                "name" => "AppVersionAccess",
                'value' => $this->getAppVersionAccess(),
                'expire' => $expired,
            ]));
        $cookies->add(new Cookie([
                "name" => "meme_sessA",
                'value' => 1,
                'expire' => $expired,
            ]));

        $user = $session->get('user');
        foreach ($user as $key => $value) {
            $cookies->add(new Cookie([
                "name" => "user[$key]",
                'value' => $value,
                'expire' => $expired,
            ]));
        }

        // добавление новой куки в HTTP-ответ
        /*
        $cookies->add(new \yii\web\Cookie([
            'name' => 'language',
            'value' => 'zh-CN',
        ]));
        */
            
        //debug(Cookies::getAll(),'cook',1); 
    }
    public function restoreSessionByCookies()
    {
        $cookies = Yii::$app->response->cookies;
        $session = Yii::$app->session;
        $user = [
            'id'=>'',
            'fio'=>'',
            'fullFio'=>'',
            'access'=>'',
        ];
        foreach ($user as $key => $value) {
            $user[$key] = $cookies->getValue("user[$key]");
        }
        $session->set('user', $user);
        $session->set('access', 1);
        $session->set('AppVersionAccess', $cookies->getValue("AppVersionAccess") );
    }

    public function accessControl() : bool
    {
        $session = Yii::$app->session;
        $cookies = Yii::$app->request->cookies;

        if ( $session->get('access') === 1 )
        {
            if ( $session->get('AppVersionAccess') !== $this->getAppVersionAccess() ) 
                return false;

            return true;
        } 

        // Если есть кука 'meme_sessA' (был установлен флаг "Запомнить меня")
        // lets check data in cookies
        if ( $cookies->getValue('meme_sessA', null) )
        {
            $this->restoreSessionByCookies();
            return true;
        }
        return false;
    }

    public function exit()
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->removeAll();

        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', 1);
                setcookie($name, '', 1, '/', $_SERVER['HTTP_HOST']);
            }
        }
        //delete user row in usersOnline table, maybe...

        return Yii::$app->session->destroy();
    }

}