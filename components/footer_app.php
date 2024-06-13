
<?php  include 'components/footer_menu.php'; ?>


</main>

    <footer class="footer_menu">
        
        <div class="footer_menu_component">
            <div class="footer_ul"><a href="myhabits.php"><img width="50px" height="auto" src="images\habits.svg">My habits</a></div>
            <div class="footer_ul"><a href="pomodoro.php"><img width="50px" height="auto" src="images\pomodoro.svg">Pomodoro</a></div>
            <div class="footer_ul"><a onclick="toggleAccountPanel()"> <img width="50px" height="auto" src="images\user.svg">My account</a></div>
        </div>
    </footer>




<script src="scripts/main.js"></script>
</body>

</html>

<?php $conn->close();?>