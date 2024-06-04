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


let workTime = 35;
let shortBreak = 5;
let longBreak = 15;
let currentTime = workTime;
let isRunning = false;
let timer;



//ta funkcja formatuje czas, aby zawsze miał dwie cyfry
function formatTime(time) {
    return time < 10 ? `0${time}` : time;
}


//ta funkcja aktualizuje wyświetlany czas
function updateDisplay() {
    let minutes = Math.floor(currentTime / 60);
    let seconds = currentTime % 60;
    document.getElementById('minutes').textContent = formatTime(minutes);
    document.getElementById('seconds').textContent = formatTime(seconds);
}


function startTimer() {
    if (!isRunning) {
        isRunning = true;
        timer = setInterval(() => {
            currentTime--;
            updateDisplay();
            if (currentTime <= 0) {
                clearInterval(timer);
                isRunning = false;
            }
        }, 1000);
    }
}

function stopTimer() {
    if (isRunning) {
        clearInterval(timer);
        isRunning = false;
    }
}

function resetTimer() {
    stopTimer();
    currentTime = workTime;
    updateDisplay();
}

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

document.getElementById('start').addEventListener('click', startTimer);
document.getElementById('stop').addEventListener('click', stopTimer);
document.getElementById('reset').addEventListener('click', resetTimer);

updateDisplay();