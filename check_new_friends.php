<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit();
}

$user_id = $_SESSION['user_id'];
$last_check = $_SESSION['last_check'] ?? time(); // Utilisez l'heure actuelle si aucune vérification n'a encore été faite.

// Recherchez des amis ajoutés après la dernière vérification
$sql = "SELECT COUNT(*) as new_friends FROM amis WHERE (id_utilisateur1 = ? OR id_utilisateur2 = ?) AND timestamp > FROM_UNIXTIME(?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $user_id, $last_check);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Mettre à jour le temps de la dernière vérification
$_SESSION['last_check'] = time();

// Envoyer les résultats
echo json_encode(['new_friends' => $data['new_friends']]);
$stmt->close();
$conn->close();
?>
