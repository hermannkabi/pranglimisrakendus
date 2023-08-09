<?php
// Start the session
session_start();
$_SESSION['level'] = 1;
// Initial value for score and level
if(!isset($_SESSION['score'])  && !isset($_SESSION['level'])){
    $_SESSION['score'] = 0;
    $_SESSION['level'] = 1;
}



// Generate random numbers
$x = random_int(0,10);
$y = random_int(0,10);

// Stopwatch 
function startTime(){
    $sec = 0;
    $min = 0;
    do {
        echo $min. ':'. $sec;
        sleep(1);
        $sec ++;
        if($sec === 60){
            $min++;
        }

    }while($_SESSION['score'] <= 15);
    if($_SESSION['score'] === 15){
        echo "Sinu aeg oli ".$min. ':'. $sec;
    }
}

// Function to verify the answer posted
function verifyAnswer(){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Reset button click, reset score and exit function
        if(isset($_POST["reset"])){
            $_SESSION["score"] = 0;
            $_SESSION["level"] = 1;
            return;
        }

        // Get answer from the form
        $answer = $_POST["answer"];

        // Check if user's answer is correct
        if ((int)$answer === $_POST["x"] + $_POST["y"]) {
            $_SESSION["score"] += 1;
            echo "Õige vastus";
        }else{
            echo "Vale vastus, õige olnuks " . $_POST["x"] + $_POST["y"];
        }
        
        // Winning condition
        if($_SESSION['score'] > 15){
            $_SESSION['score'] = 0;
            echo "<br>Sinu võit!!! Skoor lähtestatakse nulli";
        }
    }
}

//ascending level system
verifyAnswer();
if($_SESSION["score"] >= 5){
    $_SESSION["level"] = 2;
    $x = random_int(10,100);
    $y = random_int(10,100);
}
if($_SESSION["score"] >= 9){
    $_SESSION["level"] = 3;
    $x = random_int(100,1000);
    $y = random_int(100,1000);
}
if($_SESSION["score"] >= 12){
    $_SESSION["level"] = 4;
    $x = random_int(1000,10000);
    $y = random_int(1000,10000);
}


?>

<form method="post">
<p style="display: inline;">Skoor: <?php echo $_SESSION["score"] ?></p> <button style="display: inline-block;" name="reset">Nulli</button><br>
<p style="display: inline;">Tase: <?php echo $_SESSION["level"] ?></p> 

</form>

<form method="post">
    <label for="answer"><?php echo $x . "+" .$y; ?></label>

    <!-- These hidden inputs get data to the verifyAnswer method -->
    <!-- Alternatively, you could have a single hidden input with the value of the answer -->
    <input type="hidden" name="x" value=<?php echo $x ?>>
    <input type="hidden" name="y" value=<?php echo $y ?>>

    <input onclick="startTime()" id="answer" type="number" name='answer'><br> <!-- See peaks käivitama funktsiooni "startTime -->
    <button type="submit">Vasta</button>
</form> 