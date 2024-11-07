<?php
require_once 'sql/db_connect.php'; 
?>

<?php
// register_user.php
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

try {
    $stmt = $pdo->prepare("INSERT INTO Users (role, email, password, route, full_name, grade)
                           VALUES (:role, :email, :password, :route, :full_name, :grade)");
    $stmt->execute([
        ':role' => $data['role'],
        ':email' => $data['email'],
        ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ':route' => $data['route'],
        ':full_name' => $data['full_name'],
        ':grade' => $data['grade']
    ]);
    echo json_encode(["status" => "success"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
