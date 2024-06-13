<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$destinataire_pseudo = ''; // Initialiser pour éviter les erreurs si non défini

// Gérer le pseudo du destinataire pour l'affichage des messages
if (!empty($_POST['destinataire_pseudo'])) {
    $destinataire_pseudo = $_POST['destinataire_pseudo'];
} elseif (!empty($_SESSION['destinataire_pseudo'])) {
    $destinataire_pseudo = $_SESSION['destinataire_pseudo'];  // Récupérer de la session si disponible
}

// Envoi d'un message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message']) && !empty($destinataire_pseudo)) {
    $message = $_POST['message'];

    // Rechercher l'ID du destinataire basé sur son pseudo
    $sql = "SELECT id FROM utilisateurs WHERE pseudo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $destinataire_pseudo);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $destinataire_id = $row['id'];

        // Insérer le message dans la base de données
        $sql = "INSERT INTO messages (id_expediteur, id_destinataire, contenu) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $user_id, $destinataire_id, $message);
        $stmt->execute();
        echo "<p>Message envoyé avec succès!</p>";
        $_SESSION['destinataire_pseudo'] = $destinataire_pseudo;  // Sauvegarder pour utilisation ultérieure
    } else {
        echo "<p>Pseudo destinataire non trouvé.</p>";
    }
    $stmt->close();
}

// Récupération des messages uniquement avec le destinataire spécifié
$messages = [];
if (!empty($destinataire_pseudo)) {
    $sql = "SELECT m.contenu, m.timestamp, u.pseudo as expediteur FROM messages m JOIN utilisateurs u ON m.id_expediteur = u.id WHERE (m.id_destinataire = ? AND m.id_expediteur = ?) OR (m.id_destinataire = ? AND m.id_expediteur = ?) ORDER BY m.timestamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $user_id, $destinataire_id, $destinataire_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie</title>
</head>
<body>
    <h1>Messagerie</h1>
    <h2>Envoyer un message</h2>
    <form action="messages.php" method="post">
        Destinataire (Pseudo): <input type="text" name="destinataire_pseudo" required value="<?= htmlspecialchars($destinataire_pseudo) ?>"><br>
        Message: <textarea name="message" required></textarea><br>
        <input type="submit" value="Envoyer">
    </form>
    <h2>Messages reçus et envoyés avec <?= htmlspecialchars($destinataire_pseudo) ?></h2>
    <?php foreach ($messages as $message) : ?>
        <p><strong><?= $message['expediteur'] ?>:</strong> <?= $message['contenu'] ?> <em>(<?= $message['timestamp'] ?>)</em></p>
    <?php endforeach; ?>
    <p><a href="amis.php">Retour aux amis</a></p>
    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i></a></li>
</body>
</html>
