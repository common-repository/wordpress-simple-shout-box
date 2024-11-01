<?php
/**Author: Boris Ding P H 
 * Class to connect MySQL Database
 * Using PHP's PDO
 */
include('../../../wp-config.php');
class QshoutDb{
   //database info
   static $hname = DB_HOST;
   static $dbname = DB_NAME;
   static $username = DB_USER;
   static $password = DB_PASSWORD;
   static $conn;

   static public function connect() {   
     try{
        self::$conn = new PDO('mysql:host='.self::$hname.';dbname='.self::$dbname,
                         self::$username,
                         self::$password);         
      }catch(PDOException $e){
          echo $e->getMessage();
      }
     }
   }
?>

