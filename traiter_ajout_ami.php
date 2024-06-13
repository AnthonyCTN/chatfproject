<?php
include 'db.php'; 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.html"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté.
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ami_pseudo'])) {
    $ami_pseudo = $_POST['ami_pseudo'];

    // Rechercher l'ID de l'ami par son pseudo
    $sql = "SELECT id FROM utilisateurs WHERE pseudo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ami_pseudo);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ami_id = $row['id'];

        // Vérifier si déjà amis
        $sql = "SELECT * FROM amis WHERE (id_utilisateur1 = ? AND id_utilisateur2 = ?) OR (id_utilisateur1 = ? AND id_utilisateur2 = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $user_id, $ami_id, $ami_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            // Ajouter en tant qu'ami si pas déjà amis
            $sql = "INSERT INTO amis (id_utilisateur1, id_utilisateur2) VALUES (?, ?), (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiii", $user_id, $ami_id, $ami_id, $user_id);
            $stmt->execute();
            echo "Ami ajouté avec succès!";
        } else {
            echo "Vous êtes déjà amis!";
        }
    } else {
        echo "Pseudo de l'ami introuvable.";
    }
    $stmt->close();
} else {
    echo "Erreur dans la soumission du formulaire.";
}

$conn->close();
?>
