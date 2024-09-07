<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use \Firebase\JWT\JWT;
require (  __DIR__.'../../../vendor/autoload.php');
require (__DIR__.'../../src/config/db.php');

$app = AppFactory::create();
// aqui es importante agreagar el directorio donde esta el index por que si no no jala 
$app->setBasePath('/apibackend/api/login');
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
                                    FROM `user` u 
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


// end define app routes
// Run app
$app->run();




?>