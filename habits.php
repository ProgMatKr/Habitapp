<?php

require_once "database.php";


if (isset($_SESSION["logged_in"])) {


    header("Location: login.php");
    
    die();
    }

?>


<?php

session_start();

include 'components/header_app.php'; ?>

<section>
    <?php


    // Warunek wyswietla sie po nacisnieciu przycisku submit
    
    if (isset($_POST["submit"])) {
        // DEFINICJA ZMIENNYCH METODA POST
        $habitname = $_POST["habit_name"];
        $habittype = $_POST["habit_type"];


        $errors = array();
    
        // SPRAWDZENIE CZY POLA SA PUSTE
    
        if (empty($habitname)) {
            array_push($errors, "All fields are required");
        }


      
        // WYŚWIETLENIE BŁĘDÓW JEZLI ISTNIEJA TO WYSWIETL ALERT DANGER Z BŁĘDAMI A JESLI NIE TO WYKONAJ KOD
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<div class='danger_message'>$error</div>";
            }
        }


        // POLACZENIE Z BAZA DANYCH I WPROWADZENIE DANYCH DO BAZY
        else {

            $sql = "INSERT INTO habits (habit_name, habit_type) VALUES (?,?)";
            $stmt = mysqli_stmt_init($conn);
            $preperestmt = mysqli_stmt_prepare($stmt, $sql);

            if ($preperestmt) {
                mysqli_stmt_bind_param($stmt, "ss", $habitname, $habittype);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>Habit added, good luck!</div>";
                session_start();  // Rozpoczyna sesję
                $_SESSION["habit_name"] = $habitname;  // Zapisz nazwę nawyku w sesji
                echo "<div class='alert alert-info'>Your habit name is: " . $_SESSION["habit_name"] . "</div>";
            } else {
                echo "<div class='alert alert-danger'>Adding habit failed</div>";
            }
        }

    }




    
    ?>
    


    <div class="grid-container">
        <?php
        // Pobierz id zalogowanego użytkownika
        $user_id = $_SESSION['userid'];

        // Wywołanie wszystkich dodanych nawyków z bazy danych, które nie są przypisane do zalogowanego użytkownika
        $sql = "SELECT habits.* FROM habits 
        LEFT JOIN user_habits ON habits.id = user_habits.habit_id AND user_habits.user_id = $user_id
        WHERE user_habits.habit_id IS NULL";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='grid-item alert alert-info'>
             <a href='singlehabit.php?habit_id=" . $row['id'] . "'>" . $row['habit_name'] . "</a>
                <form action=\"take_habit.php\" method=\"post\">
                    <input type=\"hidden\" name=\"habit_id\" value=\"" . $row['id'] . "\">
                    <input type=\"submit\" class=\"btn btn-success\" value=\"weź nawyk\">
                </form>
             </div>";
        }
        ?>
    </div>





</section>


<?php include 'components/footer_app.php'; ?>