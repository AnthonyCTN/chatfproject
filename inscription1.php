<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Inscription</h2>
<form action="inscription.php" method="post">
    Pseudo: <input type="text" name="pseudo" required><br>
    Email: <input type="email" name="email" required><br>
    Mot de passe: <input type="password" name="password" required><br>
    <input type="submit" value="S'inscrire">
</form>

<?php
if (isset($_SESSION['message'])) {
    echo '<div class="message">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
}
?>

</body>
</html>
<style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Alignement vertical */
            height: 100vh;
            margin: 0;
        }

        form {
            background: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
            margin-bottom: 20px; /* Espacement entre le formulaire et le message */
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center; /* Centrer le texte du message */
            color: #d8000c; /* Couleur rouge pour les erreurs */
            background-color: #ffbaba; /* Fond rouge clair pour les erreurs */
            border: 1px solid #d8000c; /* Bordure rouge pour les erreurs */
            padding: 10px;
            border-radius: 5px;
            width: 300px; /* Aligner la largeur du message avec celle du formulaire */
        }
    </style>