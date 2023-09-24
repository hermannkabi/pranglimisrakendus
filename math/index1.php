<?php
// Start the session
session_start();
$_SESSION['level'] = 1;
// Initial value for score and level
if(!isset($_SESSION['score'])  && !isset($_SESSION['level'])){
    $_SESSION['score'] = 0;
    $_SESSION['level'] = 1;
}

GLOBAL $liitmine;
    
// Generate random numbers
$x = random_int(0,9);     
$y = random_int(1,10);

// // Stopwatch 
// function startTime(){
//     $sec = 0;
//     $min = 0;
//     do {
//         echo $min. ':'. $sec;
//         sleep(1);
//         $sec ++;
//         if($sec === 60){
//             $min++;
//         }

//     }while($_SESSION['score'] <= 15);
//     if($_SESSION['score'] === 15){
//         echo "Sinu aeg oli ".$min. ':'. $sec;
//     }
// }

// Function to verify the answer posted
function verifyAnswer(){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Reset button click, reset score & level and exit function
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
            unset($_POST['answer']);
        }
        
        
        // Winning condition
        if($_SESSION['score'] > 15){
            $_SESSION['score'] = 0;
            echo "<br>Sinu võit!!! Skoor lähtestatakse nulli";
        }
    }
}

//Muliplier for smoother transitions \\\ NEEDS WORK
function multyplier($var1 , $var2, $mult, $Max){
    $var2 = round($var2 * $mult);
    $var1 = round($var1 * $mult);
    $mult =+ 0.3;
    if(($var2+$var1) > $Max){
        $var2+$var1 = 0.7 * ($var2+$var1);
    };
    
}

//ascending level system
if($_SESSION["score"] >= 0){
    $_SESSION["level"] = 1;
    $x = random_int(0 ,9);
    $y = random_int(1,10);
    multyplier($x, $y, 0.5, 10);
    
}
if($_SESSION["score"] >= 5){
    $_SESSION["level"] = 2;
    $x = random_int(10 ,99);
    $y = random_int(11,100);
    multyplier($x, $y, 0.5, 100);
}
if($_SESSION["score"] >= 9){
    $_SESSION["level"] = 3;
    $x = random_int(100,999);
    $y = random_int(101,1000);
    multyplier($x, $y, 0.5, 1000);
}
if($_SESSION["score"] >= 12){
    $_SESSION["level"] = 4;
    $x = random_int(1000,9999);
    $y = random_int(1001,10000);
    multyplier($x, $y, 0.5, 10000);
}
verifyAnswer();

// Array generator
?>

<form method="post">
<button onclick="window.location.href='http://localhost:3000/korrutamine.php';">Korrutamine</button><button onclick="window.location.href='http://localhost:3000/jagamine.php';">Jagamine</button><button onclick="window.location.href='http://localhost:3000/lahutamine.php';">Lahutamine</button><br>
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