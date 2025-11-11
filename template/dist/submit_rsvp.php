<?php
$host = 'localhost';
$dbname = 'wedding_site';
$username = 'root';
$password = '';

// Връзка с MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Проверка за грешка
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверка дали е POST заявка
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Събиране на данни от формата
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $attending = trim($_POST['attending'] ?? '');
    $guest = trim($_POST['guest'] ?? '');

    // Валидация
    if ($name === '' || $email === '') {
        echo "Име и имейл са задължителни!";
        exit;
    }

    // Подготвяне на заявка
    $stmt = $conn->prepare("INSERT INTO rsvp (name, email, attending, guest) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $attending, $guest);

    if ($stmt->execute()) {
        echo "Благодарим! Отговорът ви е записан успешно.";
    } else {
        echo "Грешка при записване: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
