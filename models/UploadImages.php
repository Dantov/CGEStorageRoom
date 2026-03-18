<?php
/**
 * Created by PhpStorm.
 * User: Dant
 * Date: 01.09.2019
 * Time: 13:17
 */

namespace app\models;

use yii\web\UploadedFile;
use yii\base\Model;

class UploadImages extends Model
{

    public $image;
    public $pos_id;

    public function rules()
    {
        return [
            [
                ['UploadImages'],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg, jpeg, gif, webp',
                'maxFiles' => 20
            ],
        ];
    }

    public function upload()
    {
        if ($this->validate())
        {
            $fileNames = [];
            $i = 0;
            if ( $this->image )
            {
                $fileName = $this->pos_id."-".randomStringChars( 20, 'symbols','en')."-".$i;
                $$this->image->saveAs(_stockDIR_.'images/' . $fileName . '.' . $$this->image->extension); // UploadedFile::getInstance
                $fileNames[] = $fileName;
                $i++;
            }
            return $fileNames;
        } else {
            return false;
        }
    }


}