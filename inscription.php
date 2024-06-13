<?php
session_start();
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = $_POST["pseudo"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT id FROM utilisateurs WHERE pseudo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $pseudo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['message'] = "Ce pseudo est déjà utilisé par un autre utilisateur. Veuillez en choisir un autre.";
        header("Location: inscription1.php");
        exit();
    } else {
        $sql = "INSERT INTO utilisateurs (pseudo, email, mot_de_passe) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $pseudo, $email, $hashed_password);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Utilisateur créé avec succès. Vous pouvez vous connecter maintenant.";
            header("Location: connexion1.php");
            exit();
        } else {
            $_SESSION['message'] = "Erreur lors de la création de l'utilisateur : " . $stmt->error;
            header("Location: inscription1.php");
            exit();
        }
    }

    $stmt->close();
    $conn->close();
}
?>
