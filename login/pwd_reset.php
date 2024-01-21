<?php

if (isset($_POST["reset-pwd"])) {

    $selector = bin2hex(random_bytes(8));

    //Authenticates the user
    $token = random_bytes(32);

    $url = "localhost/...?selector=". $selector . "&validator=" . bin2hex($token);

    $expiers = date("U") + 900;

    require "database.php";

    $userEmail = $_POST["email"];

    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        //there was an error
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
    }

    $sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        //there was an error
        exit();
    } else {
        $hasedToken = passwoed_hash($token, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hasedToken, $expiers);
        mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
    //igaksjuhuks
    $mysqli_close();

    $to = $userEmail;

    $subject = "Sätestage oma uus parool Pranglimisrakenduses";

    $message = '<p>Saime päringu parooli muutmiseks. Kui teie ei esitanud seda päringut võite seda sõnumit eirata. Siin on link parooli muutmiseks: </br> </p> ';
    $message .= '<a href="' . $url . '">' . $url . '</a></p>';

    $headers = "From: Pranglimsirakendus <pranglimsirakendus@real.edu.ee>\r\n"; 
    $headers .= "Reply-To: pranglimsirakendus@real.edu.ee\r\n";
    $headers .= "Content-type: text/html\r\n";

    mail($to, $subject, $message, $headers);

    header("Location: (parooli lähtestamine õnnestus)");
} else {
    // inf
}