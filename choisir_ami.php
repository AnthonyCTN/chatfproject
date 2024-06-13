<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer la liste des amis
$amis = [];
$sql = "SELECT DISTINCT u.id, u.pseudo 
        FROM utilisateurs u 
        JOIN amis a ON (u.id = a.id_utilisateur1 AND a.id_utilisateur2 = ?) OR (u.id = a.id_utilisateur2 AND a.id_utilisateur1 = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $amis[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Choisir un ami</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Choisir un ami</h1>
        <nav>
            <ul>
                <li><a href="ch.php"><i class="fas fa-home"></i></a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i></a></li>
            </ul>
        </nav>
    </header>
    <section>
        <div class="friend-list">
            <?php foreach ($amis as $ami) : ?>
                <div class="friend-card">
                    <a href="conversation.php?ami_id=<?= $ami['id'] ?>&ami_pseudo=<?= urlencode($ami['pseudo']) ?>">
                        <div class="friend-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="friend-info">
                            <h2><?= htmlspecialchars($ami['pseudo']) ?></h2>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <a id="deconnexion" href="ch.php"><i class="fas fa-arrow-left"></i> Retour</a>
    </section>
   
</body>
</html>




<style>
   body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

header {
    background: linear-gradient(to right, #004d00, #000000);
    color: #ffffff;
    width: 100%;
    padding: 20px 0;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    z-index: 1000;
}

header h1 {
    margin: 0;
    font-size: 2em;
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 10px 0 0;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}

nav ul li {
    margin: 10px;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: linear-gradient(to right, #004d00, #000000);
    border-radius: 50%;
    transition: background-color 0.3s, color 0.3s;
}

nav ul li a:hover {
    background-color: #006600;
    color: #ffffff;
}

section {
    padding: 100px 20px 20px; /* Padding ajusté pour l'espace sous la barre de navigation */
    width: 100%;
    max-width: 800px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.friend-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.friend-card {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
    transition: transform 0.3s;
    width: 150px;
}

.friend-card:hover {
    transform: translateY(-10px);
}

.friend-card a {
    text-decoration: none;
    color: inherit;
}

.friend-avatar {
    font-size: 4em;
    color: #0073e6;
}

.friend-info h2 {
    margin: 10px 0 0;
    font-size: 1.2em;
}

#deconnexion {
    display: inline-block;
    background-color: #ff6347;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s;
    margin-top: 20px;
}

#deconnexion:hover {
    background-color: #ff2c1a;
}

footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px 0;
    width: 100%;
    position: fixed;
    bottom: 0;
}

/* Media queries pour les appareils mobiles */
@media (max-width: 768px) {
    header, footer {
        padding: 15px;
    }

    nav ul li {
        margin: 5px;
    }

    nav ul li a {
        padding: 8px 16px;
    }

    section {
        padding: 100px 15px 15px;
    }

    .friend-card {
        width: 120px;
        padding: 15px;
    }
}

@media (max-width: 480px) {
    header h1 {
        font-size: 1.5em;
    }

    nav ul li {
        margin: 3px;
    }

    nav ul li a {
        padding: 6px 12px;
        font-size: 1em;
    }

    section {
        padding: 100px 10px 10px;
    }

    .friend-card {
        width: 100px;
        padding: 10px;
    }

    #deconnexion {
        padding: 10px;
    }
}

</style>