<?php
class UserModel{
    // methode pour recuperer la liste des utilisateurs
    public static function getUserList()
    {
        // on se connecte a la base de données
        $db = self::dbConnect(); // self pour dire on est déja dans la classe
        $request = $db->prepare("SELECT *FROM users");
        try {
            $request->execute();
            //   recupere la liste
            $listUsers =  $request->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                "status" => 200,
                "message" => "voici la liste des utilisateurs",
                "users" => $listUsers
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                "status" => 500,
                "message" => $e->getMessage()

            ]);
        }
    }

    private static function dbConnect()
    {
        $conn = null;
        try {
            $conn = new PDO("mysql:host=localhost;dbname=api_db", "root", "");
        } catch (PDOException $e) {
            $conn = $e->getMessage();
        }
        return $conn;
    }

    public static function getListMessage($expeditor, $receiver)
    {

        $db = self::dbConnect();

        $request = $db->prepare("SELECT *FROM messages WHERE expeditor_id = ? AND receiver_id=? OR expeditor_id = ? AND receiver_id = ?");

        try {

            $request->execute(array($expeditor, $receiver, $receiver, $expeditor));

            $messages = $request->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([

                "status" => 200,

                "message" => "voici la liste de votrre discution",

                "listMessage" => $messages



            ]);
        } catch (PDOException  $e) {

            echo json_encode([

                "status" => 500,

                "message" => $e->getMessage()



            ]);
        }
    }

    public static function login($pseudo, $password)
    {
        //se connecter a la bd
        $db = self::dbConnect();
        //requete pour se connecter 
        $request = $db->prepare("SELECT * FROM users WHERE pseudo=?");
        // executer la requete
        try {
            $request->execute(array($pseudo));
            // recuperer la reponse de la requete
            $user = $request->fetch(PDO::FETCH_ASSOC);
            // verifier si l'utilisateur existe
            if (empty($user)) {
                echo json_encode([
                    "status" => 404,
                    "message" => "user not found"
                ]);
            } else {
                // verifier si le password est correct
                if (password_verify($password, $user['password'])) {
                    echo json_encode([
                        "status" => 200,
                        "message" => "felicitation...",
                        "userInfo" => $user
                    ]);
                } else {
                    echo json_encode([
                        "status" => 401,
                        "message" => "Mot de passe incorrect"
                    ]);
                }
            }
        } catch (PDOException $e) {
            echo json_encode([
                "status" => 500,
                "message" => $e->getMessage()
            ]);
        }
    }

    public static function register(
        $firstName,
        $lastName,
        $pseudo,
        $password
    ) {
        // crypter le mot de passe
        $passwordCrypt = password_hash($password, PASSWORD_DEFAULT);
        // connexion a la bd
        $db = self::dbConnect();
        // prepare la request
        $request = $db->prepare("INSERT INTO users(pseudo,firstname, lastname, password) VALUES (?,?,?,?)");
        //execuet
        try {
            $request->execute(array($pseudo, $firstName, $lastName, $passwordCrypt));
            echo json_encode([
                "status" => 201,
                "message" => "everything good",
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                "status" => 500,
                "message" => "internal server error",
            ]);
        }
    }

    public static function sendMessage($expeditor, $receiver, $message)
    {
        // se connecter a la bd
        $db = self::dbConnect();
        // preparer la requete
        $request = $db->prepare("INSERT INTO messages (message,expeditor_id,receiver_id) VALUES (?,?,?) ");
        // executer la requete
        try {
            $request->execute(array($message, $expeditor, $receiver));
            echo json_encode([
                "status" => 201,
                "message" => "your message is safely sent.."
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                "status" => 500,
                "message" => $e->getMessage()
            ]);
        }
    }

}