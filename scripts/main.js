function toggleAccountPanel() {
    // console.log("Funkcja toggleAccountPanel została wywołana.");
    var x = document.getElementById("account-panel");
    if (x.classList.contains("show")) {
        x.classList.remove("show");
    } else {
        x.classList.add("show");
    }
}

window.onload = function() {
    var closeMenu = document.getElementById("closeMenu");
    closeMenu.addEventListener('click', toggleAccountPanel);
}


let workTime = 25;
let shortBreak = 5;
let longBreak = 15;
let currentTime = workTime * 60;
let isRunning = false;
let timer;

// Ta funkcja formatuje czas, aby zawsze miał dwie cyfry
function formatTime(time) {
    return time < 10 ? `0${time}` : time;
}

// Ta funkcja aktualizuje wyświetlany czas
function updateDisplay() {
    let minutes = Math.floor(currentTime / 60);
    let seconds = currentTime % 60;
    document.getElementById('minutes').textContent = formatTime(minutes);
    document.getElementById('seconds').textContent = formatTime(seconds);
}

// Funkcja startuje licznik
function startTimer() {
    if (!isRunning) {
        isRunning = true;
        timer = setInterval(() => {
            currentTime--;
            updateDisplay();
            if (currentTime <= 0) {
                clearInterval(timer);
                isRunning = false;
                if (currentTime === 0) {
                    // Tylko jeśli sesja pracy zakończyła się normalnie
                    logPomodoroSession();
                    alert("Pomodoro session completed!");
                }
            }
        }, 1000);
    }
}

// Funkcja zatrzymuje licznik
function stopTimer() {
    if (isRunning) {
        clearInterval(timer);
        isRunning = false;
    }
}

// Funkcja resetuje licznik do wartości domyślnej
function resetTimer() {
    stopTimer();
    currentTime = workTime * 60;
    updateDisplay();
}

// Funkcja loguje sesję Pomodoro na serwerze
function logPomodoroSession() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "log_pomodoro.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("user_id=<?php echo $user_id; ?>");
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Aktualizacja licznika zakończonych sesji na stronie
            let countElem = document.getElementById('pomodoro-count');
            let count = parseInt(countElem.textContent, 10);
            countElem.textContent = count + 1;
        } else {
            console.error("Failed to log session.");
        }
    }
}

// Event listener dla przycisków zmieniających czas sesji
document.getElementById('work').addEventListener('click', () => {
    currentTime = workTime * 60;
    updateDisplay();
});

document.getElementById('short_brake').addEventListener('click', () => {
    currentTime = shortBreak * 60;
    updateDisplay();
});

document.getElementById('long_brake').addEventListener('click', () => {
    currentTime = longBreak * 60;
    updateDisplay();
});

// Event listener dla przycisków sterujących czasem
document.getElementById('start').addEventListener('click', startTimer);
document.getElementById('stop').addEventListener('click', stopTimer);
document.getElementById('reset').addEventListener('click', resetTimer);

// Inicjalne wyświetlenie czasu
updateDisplay();
