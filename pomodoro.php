<?php session_start(); ?>
<?php include 'components/header_app.php'; ?>




<?php
// Pobierz id zalogowanego użytkownika z sesji
$user_id = $_SESSION['userid'];

// Zapytanie SQL do pobrania liczby zakończonych sesji Pomodoro
$sql = "SELECT wykonanych_pomodoro FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Pobranie liczby wykonanych sesji Pomodoro
$completed_pomodoros = $row['wykonanych_pomodoro'];

// Zamknięcie zapytania i połączenia z bazą danych
$stmt->close();
?>
<div class="p-2 bg-white mt-2">
    <div class="  mt-1  shadow-md text-center">
        <p class="text-lg font-semibold text-gray-800">Wykonanych sesji Pomodoro:</p>
        <span id="pomodoro-count" class="text-2xl font-bold text-green-600"><?php echo $completed_pomodoros; ?></span>
    </div>


    <section class="  flex items-center justify-center">
        <div class="max-w-md w-full p-6 text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Pomodoro</h1>
            <p class="text-gray-600 mb-6">Stay focused with Pomodoro technique</p>

            <div class="flex justify-around mb-6">
                <p id="work" class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg cursor-pointer hover:bg-green-600 transition">Work</p>
                <p id="short_brake" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg cursor-pointer hover:bg-blue-600 transition">Short Break</p>
                <p id="long_brake" class="px-4 py-2 bg-purple-500 text-white font-semibold rounded-lg cursor-pointer hover:bg-purple-600 transition">Long Break</p>
            </div>

            <div class="timer mb-6">
                <div class="circle relative w-32 h-32 mx-auto flex items-center justify-center bg-gray-200 rounded-full shadow-inner">
                    <div class="time flex items-center text-2xl font-semibold text-gray-800">
                        <p id="minutes" class="flex-shrink-0">25</p>
                        <p class="px-1">:</p>
                        <p id="seconds" class="flex-shrink-0">00</p>
                    </div>
                </div>
            </div>

            <div class="controlers flex justify-around">
                <button id="start" class="px-6 py-2 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">Start</button>
                <button id="stop" class="px-6 py-2 bg-red-500 text-white font-semibold rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">Stop</button>
                <button id="reset" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">Reset</button>
            </div>
        </div>
    </section>
    </div>
<?php include 'components/footer_app.php'; ?>
