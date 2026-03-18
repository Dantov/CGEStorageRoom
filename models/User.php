<?php
namespace app\models;

use app\models\serviceTables\{Users,Permissions,Service_data};
use Yii;

class User
{
    /**
     * true если юзер не залогинился
     * @var bool
     */
    protected static bool $isGuest;
    protected static bool $isAdmin;

    protected static $userInstance;

    /**
     * ID юзера из таблицы
     * @var integer
     */

    protected static string $userThirdName;
    protected static string $userName;
    protected static string $userSurname;
    protected static int $userID;
    protected static string $userFIO;
    protected static string $userFullFIO;
    protected static string $userEmail;
    protected static string $userAbout;
    protected static string $userPicture;

    /**
     * array of all info about user roles
     * @var array
     */
    protected static array $userRoles;
    /**
     * array of some user gettin by ID
     * @var array
     */
    protected static Users $userData;

    /**
     * array Ids of models user can dowmload 3d files 
     * @var array
     */
    protected static array $userFilesAccess;

    /**
     * уровень доступа
     * @var integer
     */
    protected static int $userAccess;

    /**
     * Список разрешений для конкретного пользователя
     * all permission data for this user
     * @var
     */
    protected static array $permissions;

    /**
     * экземпляр General для доступа к не статик методам
     * @var $instance
     */
    protected static $instance;

    /**
     * @return array
     * @throws \Exception
     */
    protected static function userInstance() : array
    {
        if ( isset(self::$userInstance) && is_array(self::$userInstance) )
            if ( !empty(self::$userInstance) ) 
                return self::$userInstance;

        return self::init();
    }

    /**
     * PUBLIC METHODS
     */
    
    public static function getUsernameByID( int $id ) : string
    {
        $userdata = self::getAnyUserByID($id);
        return $userdata->fio;
    }

    public static function getAnyUserByID( int $id ) : Users
    {
        if ( isset(self::$userData) ) return self::$userData;
        
        if ( $id < 1 || $id > PHP_INT_MAX ) 
            throw new \Exception('Wrong user id',55);

        $user = Users::find()
            ->select(['id','name','lastname',
                      'thirdname','fio','fullFio',
                      'role','clients','permissions',
                      'email','about','picture','access'])
            ->where(['id' => $id]);
        if ( !$user->exists() ) {
            // Empty User
            $user = Users::find()->where(['id' => 1]);
            //throw new \Exception('No such user exists',56);
        }

        $user = $user->one();

        $user->role = json_decode($user->role,true);
        $user->clients = json_decode($user->clients,true);
        $user->permissions = json_decode($user->permissions,true);

        return self::$userData = $user;
    }

    public static function init( int $userID=null, array $user=[] ) : array
    {
        if ( isset(self::$userInstance) && is_array(self::$userInstance) ) 
            return self::$userInstance;
        
        if ( $user ) {
            return self::$userInstance = $user;
        }
        if ( $userID < 0 || $userID > PHP_INT_MAX )
        {
            throw new \Exception("We got no user sorry 123!", 510);
        }
        
        if ( !$userID )
        {
            // Try to get user by id from Session
            $user = Yii::$app->session->get('user');
            if ( !isset($user['id']) )
                throw new \Exception("We got no user sorry!", 511);
            
            $userID = $user['id'];
        }
        
        self::$userInstance = Users::find()->where(['id'=>$userID])->asArray()->one();
        
        return self::$userInstance;
    }

    public static function getFilesAccess() : array
    {
        if (isset(self::$userFilesAccess)) return self::$userFilesAccess;

        $user = self::userInstance();
        return self::$userFilesAccess = json_decode($user['files_access'],true)??[];
    }
    public static function hasFilesAccess( int $modelid ) : bool
    {
        if ( !self::hasPermission('clientFilesDownload') ) return false;
        if ( $modelid < 0 || $modelid > PHP_INT_MAX ) return false;

        return in_array($modelid,self::getFilesAccess()); 
    }

    public static function permissions() : array
    {
        if ( isset(self::$permissions) ) return self::$permissions;

        $user = self::userInstance();
        $permissions = Permissions::find()->select(['id','name','description'])->asArray()->all();  
        $userPermissions = json_decode($user['permissions'],true);

        $permittedFieldAll = [];
        foreach ( $permissions as $permission )
        {
            $pID = $permission['id'];
            if ( in_array( $pID, $userPermissions ) )
                $permittedFieldAll[$pID] = $permission;
        }

        return self::$permissions = $permittedFieldAll;
    }

    /**
     * @param string $permission
     * @return bool
     * @throws \Exception
     */
    
    public static function getPermission( mixed $permission ) : array
    {
        $permissions = self::permissions();
        if ( is_string($permission) ) 
        {
            foreach ( $permissions as $sP )
                if ( $sP['name'] === $permission ) return $sP;
        }
        if ( is_int($permission) ) {
            if ( array_key_exists($permission, $permissions) )
                return $permissions[$permission];
        }
        return [];
    }
    
    public static function hasPermission( mixed $permission ) : bool
    {
        if ( is_string($permission) ) 
        {
            foreach ( self::permissions() as $sP )
                if ( $sP['name'] === $permission ) return true;
        }
        if ( is_int($permission) ) {
            return array_key_exists($permission, self::permissions());
        }
        return false;
    }
    public static function getClientsID() : array
    {
        return json_decode(self::$userInstance['clients'],true);
    }
    public static function getClients() : array
    {
        $permissions = self::permissions();
        $uClientsID = json_decode(self::$userInstance['clients'],true);

        $clientPerm = [];
        foreach ( $permissions as $permission ) 
        {
            if ( in_array($permission['id'], $uClientsID) )
            {
                $clientPerm[ $permission['id'] ] = $permission;
            } 
        }
        return $clientPerm;
    }

    public static function getRoles( int $singleID = 0 ) : mixed
    {
        if ( isset(self::$userRoles) )
        {
            if ( $singleID )
                if ( isset(self::$userRoles[$singleID]) ) return self::$userRoles[$singleID];
            return self::$userRoles;  
        } 

        $user = self::userInstance();
        $roleIDs = json_decode($user['role']);

        $allroles = Service_data::find()->where(['tab'=>'role'])->andWhere(['in','id',$roleIDs]);
        if ( !$allroles->exists() ) return false;

        $allroles = $allroles->asArray()->all();

        foreach ( $allroles as $key => $element )
        {
            if (!isset($element['id'])) continue;
            $allroles[$element['id']] = $element;
            unset($allroles[$key]);
        }
        self::$userRoles = $allroles;

        if ( $singleID )
            if ( isset(self::$userRoles[$singleID]) ) return self::$userRoles[$singleID];
        return self::$userRoles;
    }

    /**
     * @throws \Exception
     */
    public static function isGuest() : bool
    {
        if ( isset(self::$isGuest) ) return self::$isGuest;

        return self::$isGuest = !self::getAccess() ? true : false;
    }

    /**
     * @throws \Exception
     */
    public static function isAdmin() : bool
    {
        if ( isset(self::$isAdmin) ) return self::$isAdmin;

        $role = self::getRoles(419);
        if ( !$role ) return false;
        if ( !is_array($role) ) return false;
        if ( !isset($role['name']) ) return false;

        if ( $role['name'] === 'Admin' ) return self::$isAdmin = true;
        return self::$isAdmin = false;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function getID() : int
    {
        if ( isset( self::$userID ) ) return self::$userID;

        $user = self::userInstance();
        return self::$userID = (int)$user['id'];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getSurname() : string
    {
        if ( isset( self::$userSurname ) ) return self::$userSurname;

        $user = self::userInstance();
        return self::$userSurname = $user['lastname'];
    }

    public static function getThirdName() : string
    {
        if ( isset( self::$userThirdName ) ) return self::$userThirdName;

        $user = self::userInstance();
        return self::$userThirdName = $user['thirdname'];
    }

    public static function getName() : string
    {
        if ( isset( self::$userName ) ) return self::$userName;
        $user = self::userInstance();
        return self::$userName = $user['name'];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getFIO() : string
    {
        if ( isset( self::$userFIO ) ) return self::$userFIO;

        $user = self::userInstance();
        return self::$userFIO = $user['fio'];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getFullFIO() : string
    {
        if ( isset( self::$userFullFIO ) ) return self::$userFullFIO;

        $user = self::userInstance();
        return self::$userFullFIO = $user['fullFio'];
    }
     /**
     * @return string
     * @throws \Exception
     */
    public static function getEmail() : string
    {
        if ( isset( self::$userEmail ) ) return self::$userEmail;

        $user = self::userInstance();
        return self::$userEmail = $user['email'];
    }

    public static function getAbout() : string
    {
        if ( isset( self::$userAbout ) ) return self::$userAbout;

        $user = self::userInstance();
        return self::$userAbout = $user['about'];
    }

    public static function getAvatar() : string
    {
        if ( isset( self::$userPicture ) ) return self::$userPicture;

        $user = self::userInstance();
        self::$userPicture = empty($user['picture']) ? "defaultUser2.png" : $user['picture'];

        return self::$userPicture;
    }
    

    /**
     * @return int
     * @throws \Exception
     */
    public static function getAccess() : int
    {
        if ( isset( self::$userAccess ) ) 
            return self::$userAccess;

        $user = self::userInstance();
        return self::$userAccess = (int)$user['access'];
    }

    /**
     * @return string
     */
    public static function getIp() : string
    {
        if ( filter_has_var(INPUT_SERVER, 'REMOTE_ADDR') )
        {
            return filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }
        return '';
    }

}