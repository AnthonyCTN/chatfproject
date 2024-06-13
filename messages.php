<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Envoi d'un message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['destinataire']) && isset($_POST['message'])) {
    $destinataire = $_POST['destinataire'];
    $message = $_POST['message'];

    $sql = "INSERT INTO messages (id_expediteur, id_destinataire, contenu) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $destinataire, $message);
    $stmt->execute();
    $stmt->close();
}

// Récupération des messages
$messages = [];
$sql = "SELECT m.contenu, m.timestamp, u.pseudo as expediteur FROM messages m JOIN utilisateurs u ON m.id_expediteur = u.id WHERE m.id_destinataire = ? OR m.id_expediteur = ? ORDER BY m.timestamp DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();
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
        Destinataire (ID): <input type="number" name="destinataire" required><br>
        Message: <textarea name="message" required></textarea><br>
        <input type="submit" value="Envoyer">
    </form>
    <h2>Messages reçus et envoyés</h2>
    <?php foreach ($messages as $message) : ?>
        <p><strong><?= $message['expediteur'] ?>:</strong> <?= $message['contenu'] ?> <em>(<?= $message['timestamp'] ?>)</em></p>
    <?php endforeach; ?>
    <p><a href="amis.php">Retour aux amis</a></p>
    <p><a href="deconnexion.php">Déconnexion</a></p>
</body>
</html>
