<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10.07.2018
 * Time: 20:45
 */

namespace App\Model;
use Kernel\Component\DB\MySQL;

class FilesModel {

    public static function newFile ($user_id, $filePath, $fileName, $public, $fileOldName, $createDate) {
        $data = ["user_id"=>$user_id, "file_path"=>$filePath, "file_name"=>$fileName, "public"=>$public, "old_name"=>$fileOldName, "date"=>$createDate];
        $DB = new MySQL();
        $hash = md5($filePath.User::getId().time());
        $data['hash'] = $hash;
        $result = $DB->insert('test.files', $data);
        return $result;
    }

    public static function getFileList ($user_id) {
        $DB = new MySQL();
        $result = $DB->select('files', "user_id='$user_id'" );
        return $result;
    }

    public static function getAllFiles() {
        $DB = new MySQL();
        $result = $DB->select('files', "public='1'");
        return $result;
    }

    public static function getFileInfo($fileId) {
        $DB = new MySQL();
        $result = $DB->selectOne('files', "id='$fileId'");
        $result['user_name'] = $DB->selectOne('user', "id=($result[user_id])");
        $result['user_name'] = $result['user_name']['name'];
        if (file_exists($result['file_path'])) {
            $result['file_size'] = round(filesize($result['file_path']) / 1024, 2, PHP_ROUND_HALF_UP);
        } else {
            return false;
        }
        return $result;
    }

    public static function newAccess($file_id, $duration, $hash)  {
        $date = date("Y-m-d H:i:s");
        $data = ["file_id"=>$file_id, "duration"=>$duration, "time_hash"=>$hash, "create_time"=>$date];
        $DB = new MySQL();
        $result = $DB->insert('hash', $data);
        return $result;
    }

    public static function getHash($timeHash) {
        $DB = new MySQL();
        $result = $DB->selectOne('hash', "time_hash='$timeHash'");
        return $result;
    }

    public static function getAllHashes($file_id) {
        $DB = new MySQL();
        $result = $DB->select('hash', "file_id=$file_id");
        return $result;
    }

    public static function deleteHash($hash_id) {
        $DB = new MySQL();
        $result = $DB->delete('hash', "id=$hash_id");
        return $result;
    }
}