<?php
header("Access-Control-Allow-Origin: *");
echo json_encode([

    "status" => 200,

    'message' => "ok"

]);
require_once "userController.php";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $url=$_SERVER['REQUEST_URI'];
    $url=trim($url, "\/");
    $url=explode("/",$url);
    $action=$url[1];
    if($action== "getuserlist"){
       UserController:: loadModel($action);
    }elseif($action == "geListMessage"){
        UserController:: loadModel($action,[$url[1], $url[2]]);
    }else{
        echo json_encode([
            "satus"     => 404,
            "message"   => "service not found"
        ]);
    }
} else {
   

    // ce que l'utilisateur envoi via un formulaire on recupere 
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data["action"] == "login") {
        // appel de la fonction login
      UserController::loadModel($data['action'],[$data['pseudo'], $data['password']]);
    } else if ($data["action"] == "register") {
        // on fait appel a la fonction register pour enregistrer le user
       UserController::loadModel($data['action'],[$data['firstname'], $data['lastname'], $data['pseudo'], $data['password']]);
    } else if ($data["action"] == "send message") {
        // appel de la fonction sendMessage
      UserController::loadModel($data['expeditor'], [$data['receiver'], $data['message']]);
    } else {
        echo json_encode([
            "satus"     => 404,
            "message"   => "service not found"
        ]);
    }
   

}
