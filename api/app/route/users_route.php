<?php
use App\Model\UsersModel;
use \Firebase\JWT\JWT;

    
function validateToken($request, $response, $next){
        $result = array();
        $headers = apache_request_headers();
        $key = "MyS3Cr3tKey"; //getenv('SECRET_KEY');
        
        try{
            $data=(array)JWT::decode($headers['x-access-token'], $key, array('HS256'));
            $response = $next($request, $response);
        
            return $response;
        }catch (exc $e) {
           return null; 
        }
        
        
    
    }
    
$app->group('/users/', function () {
    $this->get('', function ($req, $res, $args) {
        $um = new UsersModel();
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    })->add(validateToken);
    
    
    $this->get('{id}', function ($req, $res, $args) {
        $um = new UsersModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Get($args['id'])
            )
        );
    });
    
    $this->post('', function ($req, $res) {
        $um = new UsersModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->InsertOrUpdate(
                    $req->getParsedBody()
                )
            )
        );
    });
    $this->post('login', function ($req, $res) {
        $um = new UsersModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Login(
                    $req->getParsedBody()
                )
            )
        );
    });
    
    $this->put('{id}', function ($req, $res) {
        $um = new UsersModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->InsertOrUpdate(
                    $req->getParsedBody()
                )
            )
        );
    });
    
    $this->delete('{id}', function ($req, $res, $args) {
        $um = new UsersModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Delete($args['id'])
            )
        );
    });
    
});