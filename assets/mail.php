<?php

// Nur POST-Requests zulassen
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Pflichfeld: E-Mail
    // Wir prüfen, ob eine valide E-Mail eingegeben wurde
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Bitte eine gültige E-Mail-Adresse eingeben.";
        exit;
    }

    // Optionale Felder (falls nichts ankommt, werden sie einfach leer)
    $name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
    $subject = isset($_POST["subject"]) ? trim($_POST["subject"]) : "";
    $message = isset($_POST["message"]) ? trim($_POST["message"]) : "";

    // Wohin soll die Mail gehen?
    $recipient = "marcdior@gmx.ch";

    // Betreff der E-Mail
    $sender = "Neue Anfrage von $email";

    // E-Mail-Inhalt zusammenbauen
    $email_content  = "E-Mail: $email\n";
    $email_content .= "Name: $name\n";
    $email_content .= "Telefon: $phone\n";
    $email_content .= "Anliegen: $subject\n\n";
    $email_content .= "Nachricht:\n$message\n";

    // Header (Absender)
    // Besser ist oft eine Absenderadresse deiner eigenen Domain:
    // $headers  = "From: Deine Webseite <noreply@deine-domain.tld>\r\n";
    // $headers .= "Reply-To: $email";
    $headers = "From: $email";

    // Mail verschicken
    if (mail($recipient, $sender, $email_content, $headers)) {
        http_response_code(200);
        echo "Vielen Dank! Deine Nachricht wurde versendet.";
    } else {
        http_response_code(500);
        echo "Ups, beim Versenden ist etwas schiefgelaufen.";
    }

} else {
    // Keine POST-Anfrage -> Fehler
    http_response_code(403);
    echo "Method not allowed (bitte Formular verwenden).";
}
