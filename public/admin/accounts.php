<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password_hash'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $personnel_id = execute("SELECT personnel_id FROM Personnels WHERE name = :name", ['name' => $_POST['name']])->fetchColumn();
    if (!$personnel_id) {
        die("Personnel not found for given name.");
    }

    try {
        $existingAccountSql = "SELECT COUNT(*) FROM Accounts WHERE personnel_id = :personnel_id OR email = :email";
        $existingAccountArgs = [':personnel_id' => $personnel_id, ':email' => $email];

        $stmt = execute($existingAccountSql, $existingAccountArgs);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            die("Account with same personnel ID or email already exists.");
        }

        $sql = "
            INSERT INTO Accounts (
                personnel_id, password_hash, email
            ) VALUES (
                :personnel_id, :password_hash, :email
            );";

        $args = [
            ':personnel_id' => $personnel_id,
            ':password_hash' => $password_hash,
            ':email' => $email
        ];

        execute($sql, $args);

        header('Location: /admin/accounts.php');
        exit();
    } catch (PDOException $e) {
        die("Error inserting account: " . $e->getMessage());
    }
}

$sql = "
    SELECT
        *,
        pa.name
    FROM Accounts acc
    JOIN Personnels pa ON acc.personnel_id = pa.personnel_id
";
  
$stmt = execute($sql);
$account = $stmt->fetchAll();

$stmt = execute('SELECT name FROM Personnels');
$personnels = $stmt->fetchAll();
$options = array_map(function ($item) {
    return $item['name'];
}, $personnels);

echo $twig->render('accounts.twig', ['options'=> $options,'account' => $account, 'count' => count($account)]);
?>
