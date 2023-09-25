<?php
require_once "functions.php";
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    // echo json_encode([
    //     "status"=>200,
    //     "message"=>"ok",
    // ]);
}else{
    // echo json_encode([
    //     "test"=>"ok"
    // ]);

    // ce que l'utilisateur envoi via un formulaire on recupere 
    $data=json_decode(file_get_contents("php://input"),true);

    if ($data["action"] == "login") {
        // appel de la fonction login
        login($data['pseudo'], $data['password']);
    } else if ($data["action"] == "register") {
        // on fait appel a la fonction register pour enregistrer le user
        register($data['firstname'], $data['lastname'], $data['pseudo'], $data['password']);
    } else if ($data["action"] == "send message") {
        // appel de la fonction sendMessage
        sendMessage($data['expeditor'],$data['receiver'],$data['message']);
    } else {
        echo json_encode([
            "satus"     => 404,
            "message"   => "service not found"
        ]);
    }

    // echo json_encode([
    //     "status"=>200,
    //     "message"=>"ok",
    //     "date"=>$data

    // ]);
    
   // on fait appel a la fonction register pour enregistrer le user

  

}