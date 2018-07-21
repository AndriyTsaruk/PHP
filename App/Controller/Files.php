<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10.07.2018
 * Time: 20:39
 */

namespace App\Controller;

use App\Model\User;
use Kernel\Server\HttpRequest;
use Kernel\Core\Controller;
use App\Model\FilesModel;

class Files extends Controller {

    public function upload(HttpRequest $request) {
        $data = [];
        $data['currentMenu'] = 'upload';
        if(!empty($_FILES)) {
            $public = ($request->post('public') == 'on') ? 1 :0;
            $userId = $_SESSION['user']['id'];
            $filePath = $_FILES['upload-file']['tmp_name'];
            $fileName = $_FILES['upload-file']['name'];
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid();
            $newFileName = $newFileName.'.'.$fileExt;
            $uploadPath = ROOT_PATH.'\\uploads\\'.$newFileName;
            $uploadPath = addslashes($uploadPath);
            move_uploaded_file($filePath, $uploadPath);
            $createDate = date("Y-m-d H:i:s");
            $result = FilesModel::newFile($userId, $uploadPath, $newFileName, $public, $fileName, $createDate);
            if($result){
                $data['message'] = 'Файл '.$fileName.' успешно сохранен';
            }
        }
        $this->render('\App\View\Files\upload.html',$data);
    }

    public function myFileList(HttpRequest $request) {
        $data = [];
        $data['currentMenu'] = 'files';
        $userId = $_SESSION['user']['id'];
        $data['fileList'] = FilesModel::getFileList($userId);
        foreach ($data['fileList'] as $key=> &$value) {
            $value['allHashes'] = FilesModel::getAllHashes($value['id']);
        }
        $this->render('\App\View\Files\myFiles.html',$data);
    }

    public function allFiles(HttpRequest $request) {
        $data = [];
        $data['currentMenu'] = 'shareFiles';
        $data['fileList'] = FilesModel::getAllFiles();
        $this->render('\App\View\Files\allFiles.html',$data);
    }

    public function fileInfo() {
        if (!empty($_GET)) {
            $data = FilesModel::getFileInfo($_GET['id']);
            if (is_array($data)) {
                $data['user'] = User::getId();
                $data['currentTime'] = date("Y-m-d H:i:s");
                if (isset($_GET['time_hash'])) {
                    $result = FilesModel::getHash($_GET['time_hash']);
                    $data['create_time'] = $result['create_time'];
                    $data['duration'] = $result['duration'];
                    $data['time_hash'] = $_GET['time_hash'];
                }
            } else $data['error'] = 'Файл не найден!';
        } else $data['error'] = 'Файл не найден!';
        if (!isset($data['error'])) {
            if ((isset($_GET['hash']) AND $data['hash'] == $_GET['hash'] AND (strtotime($data['currentTime'])-86400) < strtotime($data['date']))
                OR (isset($_GET['time_hash']) AND $data['time_hash'] == $_GET['time_hash'] AND (strtotime($data['currentTime'])-86400*$data['duration']) < strtotime($data['create_time']))
                OR $data['public'] == 1
                OR ($data['user'] AND $data['user_id'] == $data['user'])) {
                $this->render('\App\View\Files\fileInfo.html', $data);
            } else $data['error'] = 'ВЫ НЕ МОЖЕТЕ ПРОСМАТРИВАТЬ ЭТО ФАЙЛ!!!';
        }
        $this->render('\App\View\Files\fileInfo.html', $data);
    }

    public function download(){
        $data = FilesModel::getFileInfo($_GET['id']);
        if (file_exists($data['file_path'])) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($data['file_path']).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($data['file_path']));
            readfile($data['file_path']);
            exit;
        }
    }

    public function durationAccess(HttpRequest $request) {
        $data = [];
        if (empty($request->get('hash_id')) AND empty($request->get('time_hash'))) {
            $file_id = $request->get('id');
            $duration = $request->get('duration');
            $hash = md5($file_id . time());
            $result = FilesModel::newAccess($file_id, $duration, $hash);
            if ($result) {
                $data ['message'] = 'Ссылка для скачивания файла:';
                $data['link'] = 'http://localhost/files/fileInfo?id=' . $file_id . '&' . 'time_hash=' . $hash;
            }
        } else {
            $result = FilesModel::getHash($request->get('time_hash'));
            $data ['message'] = 'Ссылка для скачивания файла:';
            $data['link'] = 'http://localhost/files/fileInfo?id=' . $result['file_id'] . '&' . 'time_hash=' . $result['time_hash'];
            $data['create_time'] = $result['create_time'];
            $data['duration'] = $result['duration'];
            $data['id'] = $result['id'];
        }
        $this->render('\App\View\Files\yourLink.html',$data);
    }

    public function deleteHash(HttpRequest $request) {
        FilesModel::deleteHash($request->get('id'));
        $this->myFileList($request);
    }

}