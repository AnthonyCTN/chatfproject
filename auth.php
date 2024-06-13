<?php
include 'db.php';
session_start();

$message = '';

// Traitement de l'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'signup') {
    $pseudo = $_POST["pseudo"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Vérification de l'unicité du pseudo
    $sql = "SELECT id FROM utilisateurs WHERE pseudo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $pseudo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Ce pseudo est déjà utilisé par un autre utilisateur. Veuillez en choisir un autre.";
    } else {
        $sql = "INSERT INTO utilisateurs (pseudo, email, mot_de_passe) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $pseudo, $email, $hashed_password);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $message = "Utilisateur créé avec succès. Vous pouvez vous connecter maintenant.";
        } else {
            $message = "Erreur lors de la création de l'utilisateur : " . $stmt->error;
        }
    }
    $stmt->close();
    $_SESSION['message'] = $message; // Stocker le message en session pour l'affichage après redirection
    header("Location: index.php"); // Assurez-vous que cette redirection va à la page de connexion
    exit();
}

// Traitement de la connexion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'signin') {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    $sql = "SELECT id, mot_de_passe FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php"); // Redirection vers une page protégée
            exit();
        } else {
            $message = "Mot de passe incorrect.";
        }
    } else {
        $message = "Aucun utilisateur trouvé avec cet email.";
    }
    $stmt->close();
}

$conn->close();
$_SESSION['message'] = $message; // Stocker le message en session pour l'affichage après redirection
header("Location: index.php"); // Assurez-vous que cette redirection va à la page de connexion
exit();
?>
