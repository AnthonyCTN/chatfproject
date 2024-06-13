<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.html");
    exit();
}

$results = [];
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_pseudo'])) {
    $search_pseudo = $_POST['search_pseudo'];

    $sql = "SELECT id, pseudo FROM utilisateurs WHERE pseudo LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_pseudo = "%$search_pseudo%";
    $stmt->bind_param("s", $search_pseudo);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    } else {
        $message = "Aucun utilisateur trouvé avec ce pseudo.";
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche d'utilisateurs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleg.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <h1>Recherche d'utilisateurs</h1>
        <nav>
            <ul>
                <li><a href="ch.php"><i class="fas fa-home"></i><span class="nav-text"></span></a></li>
                <li><a href="choisir_ami.php"><i class="fas fa-comments"></i><span class="nav-text"></span></a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i><span class="nav-text"></span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i></a></li>
            </ul>
        </nav>
    </header>
    <section>
        <form action="recherche.php" method="post">
            <label for="search_pseudo">Rechercher un utilisateur par pseudo:</label>
            <input type="text" id="search_pseudo" name="search_pseudo" required>
            <input type="submit" value="Rechercher">
        </form>
        <?php if (!empty($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <?php if (!empty($results)): ?>
            <h2>Résultats de la recherche:</h2>
            <ul>
                <?php foreach ($results as $result): ?>
                    <li>
                        <a href="voir_profil.php?user_id=<?= $result['id'] ?>"><?= htmlspecialchars($result['pseudo']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

</body>
</html>



<style>
   body {
    font-family: Arial, sans-serif;
    background-color: #ffffff;
    margin: 0;
    padding: 0;
    color: #333333;
    display: flex;
    flex-direction: column;
    align-items: center;
}

header {
    background-color: #0b5a43; /* Couleur verte */
    color: white;
    width: 100%;
    padding: 20px 0;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

header h1 {
    margin: 0;
    font-size: 2em;
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 20px 0 0;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}

nav ul li {
    margin: 10px;
}

nav ul li a {
    color: white;
    background-color: #0b5a43; /* Couleur verte */
    padding: 10px;
    border-radius: 50%;
    text-decoration: none;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    transition: background-color 0.3s, color 0.3s;
}

nav ul li a:hover {
    background-color: #07392a; /* Couleur verte foncée */
    color: #ffffff;
}

nav ul li a i {
    margin: 0;
}

section {
    padding: 20px;
    width: 100%;
    max-width: 800px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

form {
    width: 100%;
    max-width: 600px;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

label {
    margin-bottom: 10px;
    font-size: 18px;
    color: #0b5a43;
}

input[type="text"], input[type="submit"] {
    width: calc(100% - 20px);
    padding: 10px;
    margin-top: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #0b5a43; /* Couleur verte */
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
    border-radius: 50%; /* Bouton rond */
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

input[type="submit"]:hover {
    background-color: #07392a; /* Couleur verte foncée */
}

ul {
    list-style-type: none;
    padding: 0;
    width: 100%;
}

ul li {
    background-color: #f4f4f4;
    margin: 10px 0;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: left;
}

ul li a {
    color: #0b5a43; /* Couleur verte */
    text-decoration: none;
    font-size: 16px;
}

ul li a:hover {
    text-decoration: underline;
}

.message {
    color: #f44336;
    margin-top: 20px;
}

footer {
    width: 100%;
    background-color: #333333;
    color: white;
    text-align: center;
    padding: 10px 0;
    position: fixed;
    bottom: 0;
}

/* Media queries pour les appareils mobiles */
@media (max-width: 768px) {
    header {
        padding: 15px 20px;
    }

    nav ul li {
        margin: 5px;
    }

    nav ul li a {
        padding: 10px;
        width: 40px;
        height: 40px;
    }

    section {
        padding: 15px;
    }
}

@media (max-width: 480px) {
    header h1 {
        font-size: 1.5em;
    }

    nav ul li a {
        padding: 8px;
        font-size: 1em;
        width: 35px;
        height: 35px;
    }

    section {
        padding: 10px;
    }

    form {
        padding: 15px;
    }

    input[type="text"], input[type="submit"] {
        padding: 8px;
    }

    input[type="submit"] {
        width: 40px;
        height: 40px;
    }
}

</style>
