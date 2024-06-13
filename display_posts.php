<?php
include 'db.php';

$sql = "SELECT p.content, p.timestamp, u.pseudo FROM posts p JOIN utilisateurs u ON p.user_id = u.id ORDER BY p.timestamp DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='post'>";
        echo "<p><strong>" . htmlspecialchars($row['pseudo']) . "</strong> " . htmlspecialchars($row['timestamp']) . "</p>";
        echo "<p>" . htmlspecialchars($row['content']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>Aucun post trouvé.</p>";
}

$conn->close();
?>
