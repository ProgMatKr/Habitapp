<?php
session_start();
if(isset($_SESSION["logged_in"])) {
    header("Location: index.php");
    die();
}
?>

<?php  include 'components/header_guest.php'; ?>


        <div class="container">
            <?php
            // Sprawdza, czy formularz został wysłany metodą POST z polem "login"
            if(isset($_POST["login"])) {

                // Przypisuje zmienne $email i $password wartości z formularza
                $email = $_POST["email"];
                $password = $_POST["password"];

                // Dołącza plik bazy danych, który prawdopodobnie zawiera połączenie z bazą danych
                require 'database.php';

                // Tworzy zapytanie SQL, które wyszukuje użytkownika na podstawie podanego adresu email
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);  // Wykonuje zapytanie SQL
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);  // Pobiera wynik zapytania jako tablicę asocjacyjną

                // Sprawdza, czy użytkownik został znaleziony w bazie danych
                if($user) {

                    // Weryfikuje, czy podane hasło zgadza się z hasłem w bazie danych (zaszyfrowanym)
                    if(password_verify($password, $user["password"])) {
                        // Przekierowuje użytkownika na stronę "index.php" w przypadku poprawnego logowania
                        session_start();  // Rozpoczyna sesję
                        $_SESSION["logged_in"] = "yes";  // Ustawia zmienną sesyjną, aby zalogować użytkownika                         
                        $_SESSION["user"] = $user["username"];  // Zapisz nazwę użytkownika w sesji
                        $_SESSION["userid"] = $user["id"];  // Zapisz nazwę użytkownika w sesji

                         // Dodanie wpisu do tabeli user_logins
                            $sql = "INSERT INTO user_logins (user_id) VALUES ({$user['id']})";
                            mysqli_query($conn, $sql);

                            // Obliczenie days streak
                            $sql = "SELECT DISTINCT DATE(login_date) as login_date FROM user_logins WHERE user_id = ? AND login_date >= CURDATE() - INTERVAL 7 DAY ORDER BY login_date DESC";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            $login_dates = [];
                            while ($row = $result->fetch_assoc()) {
                                $login_dates[] = $row['login_date'];
                            }

                            $stmt->close();

                            // Sprawdzanie ciągłości logowania
                            $today = new DateTime();
                            $streak = 0;
                            $is_streak_broken = false;

                            for ($i = 0; $i < 7; $i++) {
                                $date_check = clone $today;
                                $date_check->sub(new DateInterval('P' . $i . 'D'));
                                $formatted_date = $date_check->format('Y-m-d');

                                if (in_array($formatted_date, $login_dates)) {
                                    $streak++;
                                } else {
                                    if ($i > 0) { // Przerwanie streaka
                                        $is_streak_broken = true;
                                        break;
                                    }
                                }
                            }

                            if ($is_streak_broken) {
                                $streak = 0;
                                // Możesz tutaj dodać logikę resetowania danych użytkownika w bazie danych, jeśli to potrzebne
                            }

                            $_SESSION["days_streak"] = $streak;
                                                                

                       
                        header("Location: index.php");
                        die();  // Kończy skrypt po przekierowaniu
                    } else {
                        // Wyświetla komunikat błędu, jeśli hasło jest niepoprawne
                        echo "<div class='alert alert-danger'>Password does not match</div>";
                    }

                } else {
                    // Wyświetla komunikat błędu, jeśli użytkownik o podanym adresie email nie istnieje
                    echo "<div class='alert alert-danger'>Email does not match</div>";
                }
            }?>

                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <form action="login.php" method="post">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control">

                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <button type="login" name="login" class="btn btn-primary">Login</button>


                        </form>
                        <div><p>Not registered yet?</p> <a href="registration.php" >Register Here</a></div>

                    </div>
                </div>
        </div>

<?php include 'components/footer_guest.php'; ?>
