<?php
require_once '../config/dbconnect.php';

try {
    $query = "SELECT * FROM helptek_analytics";
    $stmt = $db->query($query);
    $analytics = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($analytics);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
