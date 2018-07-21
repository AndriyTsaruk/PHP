<?php
/**
 * Created by PhpStorm.
 * User: Patsay Sergey
 * Date: 09.06.2018
 * Time: 10:59
 */

namespace Kernel\Component\DB;

class MySQL
{
    private $host = '127.0.0.1';
    private $user = 'root';
    private $password = 1111;
    private $database = 'test';

    private $connect;

    public function __construct()
    {
        $this->connect = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        if(mysqli_error($this->connect)) {
            die('Не удалось подколючиться к БД');
        }
        mysqli_set_charset($this->connect,"utf8");
    }

    public function execute($sqlQuery) {
        $result = mysqli_query($this->connect, $sqlQuery);
        if(mysqli_error($this->connect)) {
            echo mysqli_error($this->connect);
        }

        return $result;
    }

    public function select($table, $where, $fields = null) {
        if(is_array($fields)) {
            $selectStr = '';
            foreach ($fields as $key => $field) {
                if($key == 0) {
                    $selectStr = $field;
                } else {
                    $selectStr = $selectStr.', '.$field;
                }

            }
        } else {
            $selectStr = '*';
        }
        $query = 'SELECT '.$selectStr.' FROM `'.$table.'` WHERE '.$where;
        $result = $this->execute($query);
        $rows = [];
        if(mysqli_num_rows($result) > 0) {
            for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                $rows[] = mysqli_fetch_array($result);
            }
        }
        return $rows;
    }

    public function selectOne($table, $where, $fields = null) {
        $result = $this->select($table, $where, $fields);
        return (empty($result)) ? null : $result[0];
    }

    public function insert($table, $data) {
        $query = 'INSERT INTO '.$table.' (';
        foreach ($data as $key=>$value){
            $query = $query.$key.', ';
        }
        $query = substr($query, 0, -2);
        $query = $query.') VALUES (';
        foreach ($data as $key=>$value){
            $query = $query."'".$value."'".', ';
        }
        $query = substr($query, 0, -2);
        $query=$query.')';
        $result = $this->execute($query);
        return $result;
    }

    public function update($id, $table, $data) {
        $query = 'UPDATE `'.$table.'` SET';
        foreach ($data as $key=>$value) {
            $query = $query.' `'.$key.'`='.'"'.$value.'",';
        }
        $query = substr($query, 0, -1);
        $query = $query.' WHERE ID='."$id";
        $this->execute($query);
    }

    public function delete($table, $where) {
        $query = 'DELETE FROM `'.$table.'` WHERE '.$where;
        $this->execute($query);
    }
}