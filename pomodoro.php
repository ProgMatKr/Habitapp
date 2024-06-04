<?php session_start();
 include 'components/header_app.php';
?>

<section>

    <div class="pomodoro">
        <h1> Pomodoro</h1>
        <p></p>
        <div class="pom_buttons">
            <p id="work">work</p>
            <p id="short_brake">short break</p>
            <p id="long_brake">long break</p>
        </div>
        <div class="timer">
            <div class="circle">
                <div class="time">
                    <p id="minutes">25</p>
                    <p>:</p>
                    <p id="seconds">25</p>

                </div>
            </div>
        </div>
        <div class="controlers">
            <button id="start">Start</button>
            <button id="stop">Stop</button>
            <button id="reset">Reset</button>

        </div>


    </div>

</section>

<?php include 'components/footer_app.php'; ?>