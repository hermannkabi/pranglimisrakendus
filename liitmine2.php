<?php
$a = 1;

do {
    $x = random_int(0, 10);
    $y = random_int(0, 10);
    ?>
    <form action="liitmine2.php" method="post">
    <?php echo $x . "+" .$y; ?>: <input type="number" name='name'><br>
    <input type="submit" name='nupp'>
    </form> 
    <script>
      $(document).on("keypress", "form", function(event) {
         if (event.keyCode === 13) {
            event.preventDefault();
            $(this).submit();
         }
      });
   </script>

<?php 
    if (isset($_POST['nupp'])){
        unset($_POST['nupp']);
        continue;
    } else{
        die();
    }
    if((int)$_POST["name"] === $x + $y){
        $a++;
    }
} while($a <= 5);
?>