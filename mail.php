<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naam = strip_tags(trim($_POST["naam"]));
    $bedrijf = strip_tags(trim($_POST["bedrijf"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $telefoon = strip_tags(trim($_POST["telefoon"]));
    $bericht = trim($_POST["bericht"]);

    // Ontvanger
    $to = "studio@dendaz.be"; // je moet dit aanmaken
    $subject = "Nieuw contactformulier bericht van $naam";

    $body = "Naam: $naam\n";
    $body .= "Bedrijf/Band/Organisatie: $bedrijf\n";
    $body .= "E-mail: $email\n";
    $body .= "Telefoon: $telefoon\n\n";
    $body .= "Bericht:\n$bericht\n";

    $headers = "From: $naam <$email>";

    if (mail($to, $subject, $body, $headers)) {
        echo "success";
    } else {
        http_response_code(500);
        echo "error";
    }
} else {
    http_response_code(403);
    echo "Er is iets misgegaan.";
}
?>
