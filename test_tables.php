<?php
try {
    $pdo = new PDO('mysql:host=db-a1de66a8-50a7-4b04-bce5-3c35dd890132.ap-southeast-1.public.db.laravel.cloud;port=3306;dbname=main', 'rxsnfklegzlb2vgs', 'IElaiOrXje7lEEXjJkyD');
    $stmt = $pdo->query('SHOW TABLES');
    print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
