<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['ami_id'])) {
    header("Location: ch.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$ami_id = $_GET['ami_id'];
$ami_pseudo = urldecode($_GET['ami_pseudo']);

// Envoi d'un message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $_POST['message'];

    // Insérer le message dans la base de données
    $sql = "INSERT INTO messages (id_expediteur, id_destinataire, contenu) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $ami_id, $message);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['fetch_messages'])) {
    // Récupération des messages
    $messages = [];
    $sql = "SELECT m.contenu, m.timestamp, u.pseudo as expediteur, u.photo_profil, m.id_expediteur 
            FROM messages m 
            JOIN utilisateurs u ON m.id_expediteur = u.id 
            WHERE (m.id_destinataire = ? AND m.id_expediteur = ?) 
               OR (m.id_destinataire = ? AND m.id_expediteur = ?) 
            ORDER BY m.timestamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $user_id, $ami_id, $ami_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    $stmt->close();
    $conn->close();

    // Retourner les messages en JSON
    header('Content-Type: application/json');
    echo json_encode($messages);
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Conversation avec <?= htmlspecialchars($ami_pseudo) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-info">
            <h1><?= htmlspecialchars($ami_pseudo) ?></h1>
        </div>
        <div class="header-icons">
            <a href="choisir_ami.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>
    </header>
    <div class="chat-container">
        <div class="chat-header">
            <h2>Conversation avec <?= htmlspecialchars($ami_pseudo) ?></h2>
        </div>
        <div class="chat-messages" id="chat-messages">
        </div>
        <div class="chat-footer">
            <form id="messageForm" action="conversation.php?ami_id=<?= $ami_id ?>&ami_pseudo=<?= urlencode($ami_pseudo) ?>" method="post">
                <input type="text" name="message" placeholder="Votre message..." required>
                <button type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageForm = document.getElementById('messageForm');
            const chatMessages = document.getElementById('chat-messages');

            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(messageForm);

                fetch(messageForm.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    messageForm.reset();
                    fetchMessages();
                })
                .catch(error => console.error('Erreur lors de l\'envoi du message:', error));
            });

            function fetchMessages() {
                fetch('conversation.php?ami_id=<?= $ami_id ?>&ami_pseudo=<?= urlencode($ami_pseudo) ?>&fetch_messages=1')
                    .then(response => response.json())
                    .then(data => {
                        chatMessages.innerHTML = '';
                        data.forEach(message => {
                            const messageDiv = document.createElement('div');
                            messageDiv.className = message.id_expediteur == <?= $user_id ?> ? 'message-mine' : 'message-theirs';
                            messageDiv.innerHTML = `
                                <img src="${message.photo_profil}" alt="${message.expediteur}" class="profile-img">
                                <div class="message-content">
                                    <strong>${message.expediteur}:</strong>
                                    <p>${message.contenu}</p>
                                    <em>${new Date(message.timestamp).toLocaleString()}</em>
                                </div>
                            `;
                            chatMessages.appendChild(messageDiv);
                        });
                    })
                    .catch(error => console.error('Erreur lors de la récupération des messages:', error));
            }

            // Récupérer les messages toutes les 5 secondes
            setInterval(fetchMessages, 5000);
            // Récupérer les messages au chargement de la page
            fetchMessages();
        });
    </script>
</body>
</html>

<style>
/* Styles généraux */
body {
    font-family: 'Arial', sans-serif;
    background-color: #1e1e1e;
    color: #fff;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    height: 100vh;
}

header {
    background-color: #121212;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}

header img {
    border-radius: 50%;
    width: 40px;
    height: 40px;
}

header .header-info {
    display: flex;
    align-items: center;
}

header .header-info h1 {
    margin-left: 10px;
    font-size: 18px;
}

header .header-icons i {
    margin-left: 20px;
    font-size: 18px;
    cursor: pointer;
}

.chat-container {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex: 1;
    padding: 20px;
}

.chat-header {
    text-align: center;
    margin-bottom: 20px;
}

.chat-header img {
    border-radius: 50%;
    width: 80px;
    height: 80px;
}

.chat-header h2 {
    margin: 10px 0;
    font-size: 18px;
}

.chat-header button {
    background-color: #333;
    color: #fff;
    border: none;
    padding: 5px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    width: 100%;
    max-width: 600px;
    margin: auto;
    background-color: #2e2e2e;
    border-radius: 8px;
    padding: 10px;
    max-height: 300px;
    display: flex;
    flex-direction: column-reverse; 

.message-mine, .message-theirs {
    display: flex;
    align-items: flex-start;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    font-size: 14px;
    max-width: 80%;
}

.message-mine {
    background-color: #4CAF50;
    color: white;
    text-align: right;
    align-self: flex-end;
}

.message-theirs {
    background-color: #007BFF;
    color: white;
    text-align: left;
    align-self: flex-start;
}

.message-content {
    margin-left: 10px;
}

.profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.chat-footer {
    background-color: #121212;
    padding: 10px;
    display: flex;
    align-items: center;
    width: 100%;
    max-width: 600px;
    margin: auto;
}

.chat-footer form {
    display: flex;
    flex: 1;
}

.chat-footer input {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 20px;
    margin-right: 10px;
    background-color: #222;
    color: #fff;
}

.chat-footer button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 50%;
    cursor: pointer;
}

.chat-footer i {
    font-size: 24px;
    cursor: pointer;
    margin-left: 10px;
}

/* Media queries pour les appareils mobiles */
@media (max-width: 768px) {
    header .header-info h1 {
        font-size: 16px;
    }

    header .header-icons i {
        margin-left: 10px;
    }

    .chat-header h2 {
        font-size: 16px;
    }

    .chat-header button {
        padding: 4px 12px;
        font-size: 12px;
    }

    .chat-footer button {
        padding: 8px;
    }

    .chat-footer i {
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    header .header-info h1 {
        font-size: 14px;
    }

    header .header-icons i {
        margin-left: 5px;
    }

    .chat-header h2 {
        font-size: 14px;
    }

    .chat-header button {
        padding: 4px 10px;
        font-size: 10px;
    }

    .chat-footer button {
        padding: 6px;
    }

    .chat-footer i {
        font-size: 18px;
    }
}

/* Bouton Retour */
.btn-back {
    display: flex;
    align-items: center;
    background-color: #333;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s;
    font-size: 14px;
    font-weight: bold;
}

.btn-back i {
    margin-right: 8px;
}

.btn-back:hover {
    background-color: #555;
}

@media (max-width: 768px) {
    .btn-back {
        padding: 8px 16px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .btn-back {
        padding: 6px 12px;
        font-size: 10px;
    }
}
</style>
