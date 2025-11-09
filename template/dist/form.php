<?php
$numberPrefix = '[Contact via website]';
$emailTo = 'your-email@gmail.com';
$errors = [];
$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(str_replace(["\r", "\n"], '', $_POST['name'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $attending = trim($_POST['attending'] ?? '');
    $guest = trim($_POST['guest'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email is invalid.';
    }

    if ($errors) {
        $data = ['success' => false, 'errors' => $errors];
    } else {
        $subject = "=?utf-8?B?" . base64_encode("$numberPrefix - $name") . "?=";
        $body = "
            <strong>Name:</strong> $name<br />
            <strong>Email:</strong> $email<br />
            <strong>Attending:</strong> $attending<br />
            <strong>I will be alone or with:</strong> $guest<br />
        ";

        $headers  = "MIME-Version: 1.1\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: Website Form <no-reply@yourdomain.com>\r\n";
        $headers .= "Reply-To: $name <$email>\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        mail($emailTo, $subject, $body, $headers);

        $data = ['success' => true, 'message' => ''];
    }

    echo json_encode($data);
    exit;
}
?>
