<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <?php
    session_start();
    if (isset($_SESSION['message'])): ?>
        <div class="message"><?= $_SESSION['message']; ?></div>
        <?php unset($_SESSION['message']); // Effacer le message après l'affichage ?>
    <?php endif; ?>
    <form action="connexion.php" method="post">
        Pseudo: <input type="text" name="pseudo" required><br>
        Mot de passe: <input type="password" name="password" required><br>
        <input type="submit" value="Se connecter">
    </form>
    <p>Vous n'avez pas de compte ? <a href="inscription1.php">Inscrivez-vous ici</a></p>
</body>
</html>

<style>
          body {
        font-family: 'Arial', sans-serif; /* Utilisation d'une police simple et claire */
        background-color: #f0f0f0; /* Couleur de fond légère pour un look épuré */
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Utiliser toute la hauteur de la vue */
        margin: 0;
        flex-direction: column; /* Aligner les éléments en colonne */
    }

    form {
        background: white;
        padding: 20px;
        border-radius: 8px; /* Bords arrondis pour le formulaire */
        box-shadow: 0 2px 5px rgba(0,0,0,0.15); /* Ombre pour donner du relief */
        width: 300px; /* Largeur fixe pour le formulaire */
        margin-bottom: 20px; /* Espace sous le formulaire */
    }

    h2 {
        text-align: center; /* Centrer le titre */
        color: #333; /* Couleur du texte pour le titre */
        margin-bottom: 20px; /* Espacement sous le titre */
    }

    input[type="text"],
    input[type="password"] {
        width: 100%; /* Utiliser toute la largeur disponible */
        padding: 10px;
        margin-top: 8px;
        margin-bottom: 16px;
        border-radius: 5px; /* Bords légèrement arrondis pour les champs de saisie */
        border: 1px solid #ccc; /* Bordure subtile */
    }

    input[type="submit"] {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        background-color: #007BFF; /* Bleu pour le bouton de soumission */
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    input[type="submit"]:hover {
        background-color: #0056b3; /* Changement de couleur au survol */
    }

    .message {
        text-align: center; /* Centrer le texte du message */
        color: #d8000c; /* Couleur rouge pour les erreurs */
        background-color: #ffbaba; /* Fond rouge clair pour les erreurs */
        border: 1px solid #d8000c; /* Bordure rouge pour les erreurs */
        padding: 10px;
        border-radius: 5px;
        width: 300px; /* Aligner la largeur du message avec celle du formulaire */
        margin-bottom: 20px; /* Espace sous le message */
    }

    p {
        text-align: center; /* Centrer le texte */
        color: #333; /* Couleur du texte */
    }

    p a {
        color: #007BFF; /* Couleur du lien */
        text-decoration: none; /* Enlever le soulignement */
    }

    p a:hover {
        text-decoration: underline; /* Ajouter le soulignement au survol */
    }
</style>