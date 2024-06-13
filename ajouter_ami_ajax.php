<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Vous devez être connecté pour ajouter des amis.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$ami_id = $_POST['ami_id'];

$sql = "SELECT * FROM amis WHERE (id_utilisateur1 = ? AND id_utilisateur2 = ?) OR (id_utilisateur1 = ? AND id_utilisateur2 = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $user_id, $ami_id, $ami_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    $sql = "INSERT INTO amis (id_utilisateur1, id_utilisateur2) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $ami_id);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Ami ajouté avec succès!']);
    } else {
        echo json_encode(['message' => 'Erreur lors de l\'ajout de l\'ami.']);
    }
} else {
    echo json_encode(['message' => 'Vous êtes déjà amis!']);
}
$stmt->close();
$conn->close();
?>
