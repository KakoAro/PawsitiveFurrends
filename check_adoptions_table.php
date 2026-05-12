<?php
$pdo = new PDO('mysql:host=localhost;dbname=pawhome;charset=utf8mb4', 'root', '');
$stmt = $pdo->query("DESCRIBE adoptions");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Columns in adoptions table:\n";
foreach ($columns as $col) {
    echo " - " . $col['Field'] . " (" . $col['Type'] . ")\n";
}
?>