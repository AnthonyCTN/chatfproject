<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['user_id'])) {
    header("Location: connexion.html");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$user_id = $_GET['user_id'];

// Récupérer les informations de l'utilisateur
$sql = "SELECT pseudo, biographie FROM utilisateurs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Récupérer les posts de l'utilisateur
$sql = "SELECT content, timestamp FROM posts WHERE user_id = ? ORDER BY timestamp DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts_result = $stmt->get_result();
$posts = [];
while ($row = $posts_result->fetch_assoc()) {
    $posts[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de <?= htmlspecialchars($user['pseudo']) ?></title>
    <link rel="stylesheet" href="styleg.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>Profil de <?= htmlspecialchars($user['pseudo']) ?></h1>
        <nav>
            <ul>
                <li><a href="ch.php">Accueil</a></li>
                <li><a href="messages.php">Messages</a></li>
                <li><a href="conversation.php">Conversations</a></li>
                <li><a href="ajouter_ami.php">Ajouter un ami</a></li>
                <li><a href="recherche.php">Recherche</a></li>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <section>
        <h2>Informations personnelles</h2>
        <p>Pseudo: <?= htmlspecialchars($user['pseudo']) ?></p>
        <p>Biographie: <?= htmlspecialchars($user['biographie']) ?></p>
        <button id="add-friend" data-ami-id="<?= $user_id ?>">Ajouter comme ami</button>

        <h2>Posts</h2>
        <?php if (!empty($posts)): ?>
            <ul>
                <?php foreach ($posts as $post): ?>
                    <li>
                        <p><?= htmlspecialchars($post['content']) ?></p>
                        <em><?= date('Y-m-d H:i', strtotime($post['timestamp'])) ?></em>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun post trouvé.</p>
        <?php endif; ?>
    </section>
   
    <script>
        $(document).ready(function() {
            $('#add-friend').click(function() {
                const ami_id = $(this).data('ami-id');

                $.ajax({
                    url: 'ajouter_ami_ajax.php',
                    type: 'POST',
                    data: { ami_id: ami_id },
                    success: function(response) {
                        const data = JSON.parse(response);
                        alert(data.message);
                    },
                    error: function() {
                        alert('Erreur lors de l\'ajout de l\'ami.');
                    }
                });
            });
        });
    </script>
</body>
</html>

