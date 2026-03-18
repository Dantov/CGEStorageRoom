<?php
/**
 * Created by PhpStorm.
 * User: Dant
 * Date: 04.08.2019
 * Time: 0:15
 */

namespace app\models\serviceClasses;


class Backup
{


    public function backup($maxAllowedFiles = 10)
    {
        $localtime = localtime(time(), true);
        // бэкапимся только с 4х до 6
        if ( ($localtime[tm_hour]+1) < 16 || ($localtime[tm_hour]+1) >= 18 ) return;

        $today = date('Y-m-d');

        $row = mysqli_fetch_assoc( mysqli_query($this->connection, " SELECT lastdate FROM backup " ) );
        $lastDate = explode(' ', $row['lastdate'])[0];

        if ( strtotime($lastDate) < strtotime($today)  )
        {
            $backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, CHARSET);

            $this->checkBackupFiles( (int)$maxAllowedFiles, BACKUP_DIR);

            $result = $backupDatabase->backupTables(TABLES, BACKUP_DIR) ? 'OK' : 'KO';
            $backupDatabase->obfPrint('Backup result: ' . $result, 1);

            if ( $backupDatabase->done === true )
            {
                $ddddate = new DateTime('+1 hour');
                $ddmmii = $ddddate->format('Y-m-d H:i:s');

                mysqli_query($this->connection, " UPDATE backup SET lastdate='$ddmmii' ");
            }

        }
    }

    protected function checkBackupFiles($maxAllowedFiles, $backupDir)
    {
        $dir = opendir( $backupDir );
        $count = 0;
        // массив с последними датами изменения файлов
        $filesMTime = [];
        while($file = readdir($dir))
        {
            if( $file == '.' || $file == '..' || is_dir($backupDir . "/" . $file) )
            {
                continue;
            }
            $filesMTime[$count]["time"] = filectime($backupDir . "/" . $file);
            $filesMTime[$count]["name"] = $file;
            $count++;
        }

        if ( $count >= $maxAllowedFiles  )
        {
            $min = $filesMTime[0]["time"];
            $name = $filesMTime[0]["name"];
            foreach ( $filesMTime as $val  )
            {
                if ( $val["time"] < $min )
                {
                    $min = $val["time"];
                    $name = $val["name"];
                }
            }
            unlink($backupDir . "/" . $name);
        }

        //debug( $name . " " . date("F d Y H:i:s.", $min) , "ggggggg");
    }

}