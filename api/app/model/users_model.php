<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;
use \Firebase\JWT\JWT;



class UsersModel
{
    private $db;
    private $table = 'users';
    private $response;
    
    private $key = "MyS3Cr3tKey"; //getenv('SECRET_KEY');
    
    public function __CONSTRUCT(){
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    
    public function GetAll(){
		try{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table");
			$stm->execute();
            
			$this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();
            
            return $this->response;
		}catch(Exception $e){
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}
    }
    
    public function Get($id){
		try{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
			$stm->execute(array($id));

			$this->response->setResponse(true);
            $this->response->result = $stm->fetch();
            
            return $this->response;
		}
		catch(Exception $e){
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}  
    }
    
    public function Login($data){
		try{
            $result = array();

			$stm = $this->db->prepare("SELECT password FROM $this->table WHERE email = ?");
			$stm->execute(array(
                            $data['email']
                            ));
                            
            $password = $stm->fetchColumn();
                            
            if($password!=null){  
                
                if (password_verify($data['password'], $password)) {
                    $time = time();
                    $token = array(
                       'iat' => $time, // Tiempo que inició el token
                        'exp' => $time + (60*60), // Tiempo que expirará el token (+1 hora)
                        'data' => [ // información del usuario
                            'email' => $data['email']
                        ]
                    );
                    $jwt = JWT::encode($token, $this->key);
                    
                    $this->response->setResponse(true);
                    $this->response->message = "User logged key:". $this->key;
                    $this->response->token = $jwt;
                }else{
                    $this->response->setResponse(false);
                    $this->response->message = "Invalid credentials";
    		    } 
    		}else{
                $this->response->setResponse(false);
                $this->response->message = "Invalid credentials";
    		}   
            
            return $this->response;
		}
		catch(Exception $e)
		{
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}  
    }
    
    public function InsertOrUpdate($data){
		try 
		{
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            
            if(isset($data['id']))
            {
                $sql = "UPDATE $this->table SET 
                            name          = ?,
                            email          = ?,
                            password            = ?
                        WHERE id = ?";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['name'], 
                            $data['email'],
                            $password,
                            $data['id']
                        )
                    );
            }
            else
            {
                $sql = "INSERT INTO $this->table
                            (name, email, password)
                            VALUES (?,?,?)";
                
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['name'], 
                            $data['email'],
                            $password
                        )
                    ); 
            }
            
			$this->response->setResponse(true);
            $this->response->result = "created";
            $this->response->message = "User created";
            return $this->response;
		}catch (Exception $e) 
		{
            $this->response->setResponse(false, $e->getMessage());
		}
    }
    
    public function Delete($id){
		try{
			$stm = $this->db
			            ->prepare("DELETE FROM $this->table WHERE id = ?");			          

			$stm->execute(array($id));
            
			$this->response->setResponse(true);
            return $this->response;
		} catch (Exception $e) 
		{
			$this->response->setResponse(false, $e->getMessage());
		}
    }
}