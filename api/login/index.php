<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
require (  __DIR__.'../../../vendor/autoload.php');
require (__DIR__.'../../src/config/db.php');

$app = AppFactory::create();
// aqui es importante agreagar el directorio donde esta el index por que si no no jala 
$app->setBasePath('/slimsso/api/login');
$app->addRoutingMiddleware();
/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Init define app routes
// este metodo inicial tambien deberiamos escribirlo segun el dominio $response->getBody()->write("API LOGIN");
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("API LOGIN");
    return $response;
});

$app->get('/codigos', function (Request $request, Response $response, $args) {
    $response->getBody()->write("
    COD_WRN  LOG001 : USUARIO NO EXISTE EN BD O NO ESTA ASOCIADO AL SISTEMA
    COD_ERR  LOG001 : SECRET NO COINCIDE
    ");
    return $response;
});

$app->get('/enc/{secret}', function (Request $request, Response $response, $args) {
  
    //con este codigo al dar de alta los usuarios 
    $secret =  password_hash( $args['secret'],PASSWORD_BCRYPT, ['cost' => 12,]);  
    $response_data = [
        'secret'  => $args['secret'],        
        'encripted'  => $secret,        
    ];
    $response->getBody()->write( json_encode($response_data));
    return $response->withHeader('Content-Type','application/json');
});

$app->post('/login', function (Request $request, Response $response, $args) use ($pdo) {

    $body = $request->getBody();
    $data = json_decode($body, true);

    $consulta = $pdo->prepare("SELECT u.`user` 
                                    , u.`password`
                                    , u.`name` usrname
                                    , u.lastname 
                                    , u.secondlastname
                                    , u.email
                                    , us.`status`
                                    , sy.`name` sysname
                                    FROM `usersystem` u 
                                    INNER JOIN `user_system` us  ON us.`user` = u.`id`
                                    INNER JOIN `system` sy ON sy.id_system = us.`system`
                                    WHERE u.`user` = :userParam
                                    AND  sy.`name` =:systemParam");
      
     $consulta->bindParam(':userParam',$data['user']);
     $consulta->bindParam(':systemParam',$data['system']);
     $consulta->execute();
     $user = $consulta->fetch(PDO::FETCH_ASSOC);
if($user){

    $secret_Key  = '68V0zWFrS72GbpPreidkQFLfj4v9m3Ti+DXc8OB0gcM=';
    $date   = new DateTimeImmutable();
    $expire_at     = $date->modify('+30 minutes')->getTimestamp();  
    $domainName = "your.domain.name";
    
    $usr = $user['user'];
    $usrname   = $user['usrname'];
    $lastname = $user['lastname'];
    $secondlastname = $user['secondlastname'];
    $email = $user['email'];
    $status = $user['status'];
    $sysname = $user['sysname'];
    $hash = $user['password'];
    $passEnviado = $data['secret'];  

    if(password_verify($passEnviado, $hash)){

       $request_data = [
           'iat'  => $date->getTimestamp(),         // Issued at: time when the token was generated
           'iss'  => $domainName,                       // Issuer
           'nbf'  => $date->getTimestamp(),         // Not before
           'exp'  => $expire_at,                           // Expire
           'user' => $usr,
           'usrname' => $usrname,
           'lastname' => $lastname,
           'secondlastname' => $secondlastname,
           'email' => $email,
           'sysname' => $sysname,
           'status' => $status,
           
       ];
     $jwt =  JWT::encode(
          $request_data,
          $secret_Key,
          'HS512'
      );
       //secret

 
       $response_data = [
          'msg'  => "OK",         // Issued at: time when the token was generated
          'token'  => $jwt,                       // Issuer
      ];

    }else{

       $response_data = [
           'msg'  => "COD_ERR  LOG001",         // Issued at: time when the token was generated
           'token'  => "",                       // Issuer
       ];
    }
    

}else{
    $response_data = [
        'msg'  => "COD_WRN  LOG001",         // Issued at: time when the token was generated
        'token'  => "",                       // Issuer
    ]; 
}

    $pdo=null;

    $response->getBody()->write(json_encode($response_data));
    return $response->withHeader('Content-Type','application/json');
});

//metodos CRUD

$app->post('/adduser', function (Request $request, Response $response, $args) use ($pdo) {
    $headers = getallheaders();
    $headerAut = $headers['Authorization'];
    $body = $request->getBody();
    $data = json_decode($body, true);
    try {
        $secret_Key  = '68V0zWFrS72GbpPreidkQFLfj4v9m3Ti+DXc8OB0gcM=';
        list($type, $jwt) = explode(' ', $headerAut, 2);
            if (strcasecmp($type, 'Bearer') == 0) {
            $decoded = JWT::decode($jwt, new Key($secret_Key, 'HS512'));
            $decoded_array = (array) $decoded;

            }
//aqui inicia la generacion automatica de codigo
    $user=$data['user'];
	$password=$data['password'];
	$name=$data['name'];
	$lastname=$data['lastname'];
	$secondlastname=$data['secondlastname'];
	$email=$data['email'];
	$status=$data['status'];

    //FALTA VALIDAR
    $passwordencriptada = password_hash($password,PASSWORD_BCRYPT, ['cost' => 12,]);
/*
   $consulta = $pdo->prepare("INSERT INTO `usersystem`(`ID`,`user`,`password`,`name`,`lastname`,`secondlastname`,`email`,`status`,`modifydate`,`modifyuser`)
                                VALUES (NULL,':user',':password',':name',':lastname',':secondlastname',':email',':status',NOW(),':modifyuser')");
  
        
        
        
        
       
        
        ;*/

        $consulta = $pdo->prepare("INSERT INTO `usersystem`(`user`,`password`,`name`,`lastname`,`secondlastname`,`email`,`status`,`modifydate`,usrupd) 
        VALUES (:user,:password,:name,:lastname,:secondlastname,:email,:status,NOW(),:usrupd)");
        $consulta->bindParam(':user',$data['user']);
        $consulta->bindParam(':password',$passwordencriptada);
        $consulta->bindParam(':name',$data['name']);
        $consulta->bindParam(':lastname',$data['lastname']);
        $consulta->bindParam(':secondlastname',$data['secondlastname']);
        $consulta->bindParam(':email',$data['email']);
        $consulta->bindParam(':status',$data['status']);
        $consulta->bindParam(':usrupd',$decoded_array['user']);
        $consulta->execute();



        
        $user_id = $pdo->lastInsertId();
        if($user_id != 0){
            $body = [   'id' => $user_id 
            , 'name' => $data['name']
            , 'lastname' => $data['lastname'] 
            , 'secondlastname' => $data['secondlastname'] 
            , 'email' => $data['email'] 
            , 'status' => $data['status'] 
        ];
            $responseDto = ['Codigo' => 1, 'msg' => 'USUARIO CREADO CORRECTAMENTE', 'body' => $body ];
        }else{
            $responseDto = ['Codigo' => 2, 'msg' => 'OCURRIO UN ERROR', 'body' => ''];
        }


//aqui inicia la generacion automatica de codigo
            $response->getBody()->write( json_encode($responseDto)  );

    } catch (Exception $e) {
        $response->getBody()->write( json_encode(['Codigo' => 2, 'msg' => $e->getMessage() , 'body' => '']) );
    } finally {
        return $response;      
    }


  return $response->withHeader('Content-Type','application/json');
});





$app->get('/test', function (Request $request, Response $response, $args) {

    $key = '68V0zWFrS72GbpPreidkQFLfj4v9m3Ti+DXc8OB0gcM=';
    $date   = new DateTimeImmutable();
    $expire_at     = $date->modify('+30 minutes')->getTimestamp();  
    $domainName = "your.domain.name";
    $usr ="juanma";
    $usrname="juanma";
    $lastname="juanma";
    $secondlastname="juanma";
    $email="juanma";
    $sysname="juanma";
    $status="juanma";


    
    $payload = [
        'iat'  => $date->getTimestamp(),         // Issued at: time when the token was generated
        'iss'  => $domainName,                       // Issuer
        'nbf'  => $date->getTimestamp(),         // Not before
        'exp'  => $expire_at,
        'user' => $usr,
        'usrname' => $usrname,
        'lastname' => $lastname,
        'secondlastname' => $secondlastname,
        'email' => $email,
        'sysname' => $sysname,
        'status' => $status,
 ];
    
 $jwt = JWT::encode($payload, $key, 'HS256');
    
     
 $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
 //print_r($decoded);
 
 
     $response->getBody()->write( $jwt);
     return $response;
});

// end define app routes
// Run app
$app->run();




?>