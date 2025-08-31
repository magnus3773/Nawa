<?php

class ContactForm {
    private $recipient;
    private $fromName;
    private $fromEmail;

    public function __construct($recipient, $fromName, $fromEmail) {
        $this->recipient = $recipient;
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
    }

    public function sendEmail($name, $email, $phone, $subject, $message) {
        $email_content = $this->buildEmailContent($name, $email, $phone, $subject, $message);
        $email_headers = $this->buildEmailHeaders();

        if (mail($this->recipient, $subject, $email_content, $email_headers)) {
            http_response_code(200);
            echo "Vielen Dank! Ihre Nachricht wurde erfolgreich versendet.";
        } else {
            http_response_code(500);
            echo "Ups! Etwas ist schiefgelaufen – Ihre Nachricht konnte nicht gesendet werden.";
        }
    }

    private function buildEmailContent($name, $email, $phone, $subject, $message) {
        $content = "";
        $fields = array(
            "Name"    => $name,
            "E-Mail"  => $email,
            "Telefon" => $phone,
            "Betreff" => $subject,
            "Nachricht" => $message
        );
        foreach ($fields as $fieldName => $fieldValue) {
            if (!empty($fieldValue)) {
                $content .= "$fieldName: $fieldValue\r\n\r\n";
            }
        }
        return $content;
    }

    private function buildEmailHeaders() {
        // UTF-8 und Plaintext, damit Umlaute korrekt sind
        $headers  = "From: {$this->fromName} <{$this->fromEmail}>\r\n";
        $headers .= "Reply-To: {$this->fromEmail}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        return $headers;
    }
}

// HINWEIS: Hier Empfänger/Absender anpassen
$recipient = "support@envato.com";   // z.B. "magnus_business@outlook.com"
$fromName  = "RRDevs";               // z.B. "Alpen Intelligenz"
$fromEmail = "hellow@rrdevs.net";    // z.B. "magnus_business@outlook.com"

$contactForm = new ContactForm($recipient, $fromName, $fromEmail);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST["phone"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["textarea"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Bitte überprüfen Sie Ihre Eingaben und versuchen Sie es erneut.";
        exit;
    }

    $contactForm->sendEmail($name, $email, $phone, $subject, $message);
} else {
    http_response_code(403);
    echo "Bei der Übermittlung ist ein Problem aufgetreten. Bitte versuchen Sie es erneut.";
}