<?php
namespace App\Lib;

use PDO;

class Database
{
    public static function StartUp()
    {
        $db_host="localhost";//getenv('DB_HOST');
        $db_port="3306";//getenv('DB_PORT');
		$db_name="MyApp";//getenv('DB_NAME');
        $db_user="root";//getenv('DB_USER');
		$db_password="MyS3Cr3tKey";//getenv('DB_PASSWORD');
		$pdo = new PDO('mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8', $db_user, $db_password);
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        
        return $pdo;
    }
}