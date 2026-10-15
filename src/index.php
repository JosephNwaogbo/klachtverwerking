<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

//var_dump($_ENV);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'] ?? '';
    $email = $_POST['email'] ?? '';
    $omschrijving = $_POST['omschrijving'] ?? '';

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host     = $_ENV['MAIL_HOST'];
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->Port     = $_ENV['MAIL_PORT'];


        $mail->setFrom('klachten@voorbeeld.nl', 'Klachtenservice');
        $mail->addAddress($email);
        $mail->addCC($_ENV['MAIL_CC']);


        $mail->Subject = 'Uw klacht is in behandeling';
        $mail->Body = "Naam: $naam\nE-mail: $email\nOmschrijving: $omschrijving";

        $mail->send();
        echo 'Je klacht is verzonden.';
    } catch (Exception $e) {
        echo "Mail niet verstuurd: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <title>Klachtenformulier</title>
</head>

<body>
    <h1>Klachtenformulier</h1>
    <form action="index.php" method="POST">
        <p>
            <label>Naam<br>
                <input type="text" name="naam" required>
            </label>
        </p>
        <p>
            <label>E-mail<br>
                <input type="email" name="email" required>
            </label>
        </p>
        <p>
            <label>Omschrijving klacht<br>
                <textarea name="omschrijving" rows="5" required></textarea>
            </label>
        </p>
        <input type="submit" value="Verstuur klacht">
    </form>
</body>

</html>