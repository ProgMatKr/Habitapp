
<?php
session_start();
if(isset($_SESSION["logged_in"])) {
    header("Location: index.php");
    die();
}

require_once "database.php";

?>


<?php include 'components/header_guest.php'; ?>

<section>
    <div class="container">


    <?php


    // Warunek wyswietla sie po nacisnieciu przycisku submit

    if (isset($_POST["submit"])) {




        // DEFINICJA ZMIENNYCH METODA POST
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordConf = $_POST["passwordConf"];
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();
        // DEFINICJA ZMIENNYCH METODA POST



        // SPRAWDZENIE CZY POLA SA PUSTE

            if(empty($username) or empty($email) or empty($password) or empty($passwordConf)){
                array_push($errors, "All fields are required");
            }


            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                array_push($errors, "Email is invalid");
            }

            // SPRAWDZENIE ZGODNOSCI HASLA
            if($password !== $passwordConf){
                array_push($errors, "Passwords do not match");
            }

            // SPRAWDZENIE DŁUGOSCI HASLA
            if(strlen($password) < 8){
                array_push($errors, "Password must be at least 8 characters");
            }


        // SPRAWDZENIE CZY EMAIL JUZ ISTNIEJE
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);

            if($rowCount > 0){
                array_push($errors, "Email already exists");
            }



            // SPRAWDZENIE CZY USERNAME JUZ ISTNIEJE
            $sql = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);

            if($rowCount > 0){
                array_push($errors, "Username already exists");
            }

        

        // WYŚWIETLENIE BŁĘDÓW JEZLI ISTNIEJA TO WYSWIETL ALERT DANGER Z BŁĘDAMI A JESLI NIE TO WYKONAJ KOD
            if(count($errors) > 0){
                Foreach($errors as $error){
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
            
            // POLACZENIE Z BAZA DANYCH I WPROWADZENIE DANYCH DO BAZY
            else{
                
                $sql = "INSERT INTO users (username, email, password) VALUES ( ?,?,?)";
                $stmt = mysqli_stmt_init($conn);
                $preperestmt = mysqli_stmt_prepare($stmt, $sql);

                if($preperestmt){
                    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $passwordhash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>Registration successful</div>";
                }else{
                    echo "<div class='alert alert-danger'>Registration failed</div>";
                }
        }

    }


    ?>
        <form action="registration.php" method="post">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>

                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>


                <div class="form-group">
                    <input type="password" name="passwordConf" class="form-control" placeholder="Confirm Password" required>
                </div>

                <div class="form-button">
                    <button type="submit" name="submit" class="register_button">Register</button>
                </div>



        </form>

        <div><p>Already registered</p> <a href="login.php" >Login Here</a></div>

    </div>
</section>

<?php include 'components/footer_guest.php'; ?>
