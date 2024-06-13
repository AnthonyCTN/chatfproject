<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un ami</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php
    include 'db.php';
    session_start();

    $message = '';
    $messageClass = ''; // Classe pour le style du message

    if (!isset($_SESSION['user_id'])) {
        header("Location: connexion.html");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ami_pseudo'])) {
        $ami_pseudo = $_POST['ami_pseudo'];

        $sql = "SELECT id FROM utilisateurs WHERE pseudo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ami_pseudo);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $ami_id = $row['id'];

            $sql = "SELECT * FROM amis WHERE (id_utilisateur1 = ? AND id_utilisateur2 = ?) OR (id_utilisateur1 = ? AND id_utilisateur2 = ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiii", $user_id, $ami_id, $ami_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                $sql = "INSERT INTO amis (id_utilisateur1, id_utilisateur2) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $user_id, $ami_id);
                $stmt->execute();
                $message = "Ami ajouté avec succès!";
                $messageClass = 'message-success';
            } else {
                $message = "Vous êtes déjà amis!";
                $messageClass = 'message-error';
            }
        } else {
            $message = "Pseudo de l'ami introuvable.";
            $messageClass = 'message-error';
        }
        $stmt->close();
    }
    $conn->close();
    ?>
    <header>
        <h1>Ajouter un ami</h1>
        <nav>
            <ul>
                <li><a href="ch.php"><i class="fas fa-home"></i></a></li>
                <li><a href="choisir_ami.php"><i class="fas fa-comments"></i></a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i></a></li>
            </ul>
        </nav>
    </header>
    <section>
        <form action="ajouter_ami.php" method="post">
            <label for="ami_pseudo">Pseudo de l'ami:</label>
            <input type="text" id="ami_pseudo" name="ami_pseudo" required>
            <input type="submit" value="Ajouter">
        </form>
        <?php if (!empty($message)): ?>
            <p class="<?= $messageClass ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </section>
    <footer>
        <p>Application de Messagerie © 2024</p>
    </footer>
</body>
</html>


<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
}

header {
    background: linear-gradient(to right, #004d00, #000000);
    color: #ffffff;
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
    padding: 20px;
    width: 100%;
    max-width: 600px;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px 0;
    text-align: center;
}

label {
    font-size: 18px;
    margin-bottom: 10px;
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
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

.message-success {
    color: #4CAF50;
    margin-top: 20px;
}

.message-error {
    color: #f44336;
    margin-top: 20px;
}

footer {
    width: 100%;
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px 0;
    position: fixed;
    bottom: 0;
}

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
        padding: 10px;
    }

    .card {
        padding: 15px;
    }
}

</style>