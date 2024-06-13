<?php
include 'db.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = $_POST["pseudo"];
    $password = $_POST["password"];

    $sql = "SELECT id, mot_de_passe FROM utilisateurs WHERE pseudo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $pseudo);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            // Redirection vers la page des amis après une connexion réussie
            header("Location: ch.php");
            exit();
        } else {
            $_SESSION['message'] = "Mot de passe incorrect!";
        }
    } else {
        $_SESSION['message'] = "Pseudo introuvable!";
    }

    $stmt->close();
    $conn->close();

    // Rediriger vers la page de connexion pour afficher le message
    header("Location: connexion1.php");
    exit();
}
?>