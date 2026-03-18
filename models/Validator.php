<?php
/**
 * Date: 05.01.2021
 * Time: 22:22
 */
namespace app\models;

use Yii;

class Validator
{
    protected string $behavior = '';
    protected static $lastError = '';
    protected static $errors = [];

    protected $fieldRules = [];

    protected $badChars = ['\'', '\\', '/', '"', '%','&','?','*','|','^', '<', '>', ':',';','`','+','='];

    /**
     * Table name bad chars
     * @var array
     */
    private $tnbc = ['\'','"', ',', '\\','/', '|', '<', '>','+','?','&','*','(',')','{','}',':',';','^','`'];

    protected string $pattern = "/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

    /**
     * Validator constructor.
     * @throws \Exception
     */
    public function __construct( string $behavior = '' )
    {
        if ( $behavior ) $this->behavior = $behavior;
    }

    public function getAllErrors()
    {
        return self::$errors;
    }
    public function getLastError()
    {
        return self::$lastError;
    }

    /**
     * Удаляем всю инф. об ошибках
     * что бы валидировать новые поля
     * @return bool
     */
    public function reset() : bool
    {
        self::$lastError = '';
        self::$errors = [];
        if ( empty(self::$lastError) && empty(self::$errors) )
            return true;

        return false;
    }

    public function baseValidate( string $str ) : string
    {
        $str = trim($str);
        $str = strip_tags($str);
        return $str;
    }

    public function sanitarizePost( string $postname) : string
    {
        $postname = trim($postname);
        return filter_input(INPUT_POST, $postname, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function validateEmail( string $email, array $usersAll ) : bool
    {
        if ( preg_match($this->pattern, $email) ) 
        {
            if ( $this->behavior === 'add user' ) {
                foreach( $usersAll as $singleUser )
                {
                    if ( $singleUser['email'] == $email ) {
                       Yii::$app->session->setFlash('emailexist','User with this email already exist!');
                       return false;
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function validateString( string $string) : bool
    {
        //[-a-zA-Z0-9_
        if ( empty(trim($string)) ) return false;

        $symbols = preg_split('//u',$string,-1,PREG_SPLIT_NO_EMPTY);
        foreach ( $symbols as $key => $symbol )
        {
            if ( in_array($symbol, $this->badChars) )
                return false;
        }
        return true;
    }
    public function validateLogInput( string $log, array $usersAll ) : bool
    {
        if ( empty(trim($log)) ) return false;

        $symbols = preg_split('//u',$log,-1,PREG_SPLIT_NO_EMPTY);
        $len = count($symbols);
        if ( $len < 6 || $len > 25 ) return false;
        foreach ( $symbols as $key => $symbol )
        {
            if ( in_array($symbol, $this->badChars) )
                return false;
        }

        if ( $this->behavior === 'add user' )
        {
            foreach( $usersAll as $singleUser )
            {
                if ( $singleUser['login'] == $log ) {
                   Yii::$app->session->setFlash('logexist','User with this login already exist!');
                    return false;
                }
            }    
        }

        return true;
    }
    public function validatePassInput( string $pass) : bool
    {
        $symbols = preg_split('//u',$pass,-1,PREG_SPLIT_NO_EMPTY);
        $len = count($symbols);
        if ( $len < 8 || $len > 60 ) return false;
        return true;
    }
    public function validateLogin( string $loginName) : string
    {
        $login = filter_input(INPUT_POST, $loginName, FILTER_SANITIZE_SPECIAL_CHARS);
        //$userData['login'] = trim( htmlentities($userData['login']) );
        return $login;
    }
    public function validatePassword( string $passwordName) : string
    {
        return filter_input(INPUT_POST, $passwordName, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function sanitizeFileName($name) : string
    {
        $name = $this->baseValidate($name);
        $name = cyrillicToLatin($name);
        /*
        $symbols = preg_split('//u',$name,-1,PREG_SPLIT_NO_EMPTY);
        foreach ( $symbols as $key => $symbol )
        {
            if ( in_array($symbol, $this->badChars) )
            {
                unset($symbols[$key]);
            }
        }
        $str = implode('',$symbols);
        */
        if (mb_strlen($name) > 25) {
            $name = mb_substr($name,0,24);
        }
        return $name;
    }

    private function setErrorText( string $rule, string $ruleName, $value )
    {
        self::$lastError = $this->rulesErrorText($rule, $ruleName, $value);
        self::$errors[] = self::$lastError;
    }

    public function validateTableName( string $tName ) : bool
    {
        $tName = $this->baseValidate($tName);

        // проверить каждый символ поля
        $symbols = preg_split('//u',$tName,-1,PREG_SPLIT_NO_EMPTY);

        foreach ( $symbols as $symbol )
        {
            if ( in_array($symbol, $this->tnbc) )
                return false;
        }

        return true;
    }

    /** Search Input Trusted Chars */
    protected array $sitc = [];
    public function searchInputTrustedChars() : array
    {
        
        $res[] = chr(45);
        for ( $i = 48; $i <= 122; $i++ )
        {
            if ( $i === 58 ) $i = 65;  
            if ( $i === 91 ) $i = 97;
            $res[] = chr($i);
        }
        return $res;
        debug($res,'res',1);
        //if ( !empty(self::$sitc) ) return self::$sitc;

        for ( $i = 1025; $i <= 1111; $i++ )
        {
            $charsMB[$i] = mb_chr($i,'utf-8');
        }
        debug($charsMB,'charsMB',1);
        for ( $i = 48; $i <= 122; $i++ )
        {
            if ( $i === 58 ) $i = 65;
            if ( $i === 91 ) $i = 97;
            $charASCII[] = chr($i);
        }
        debug($charASCII,'charsASCII',1);
        $needs = [33,35,36,38,40,41,42,43,45,58,59,64,124];
        foreach( $needs as $need )
        {
            $restChars[] = chr($need);
        }
        //debug($restChars,'restChars',1);
        return $this->sitc = [...$restChars, ...$charASCII, ...$charsMB];
    }
    public function filterSearchInput( string $sinput ) : string
    {
        $sitc = $this->searchInputTrustedChars();
        $sinput_chars = mb_str_split($sinput, 1,"utf-8");
        
        //debug($sinput_chars,'$sinput_chars',1);
        
        foreach($sinput_chars as $k => $char)
        {
            if ( !in_array($char, $sitc) )
                unset($sinput_chars[$k]);
        }
        return implode('',$sinput_chars);
    }
    

}