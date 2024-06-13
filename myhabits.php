<?php
if (isset($_SESSION["logged_in"])) {


    header("Location: login.php");

    die();
}
session_start();

require 'database.php';

?>
<?php include 'components/header_app.php'; ?>



<section>
    <?php 
    if (isset($_SESSION["message"])) {
        echo $_SESSION["message"];
        unset($_SESSION["message"]);
    }


    ?>


<div class="max-w-lg mt-2 mx-auto p-6 bg-white rounded-lg shadow-lg">
    <form action="add_habit_form.php" method="post">
        <div class="mb-4">
            <input 
                type="text" 
                name="habit_name" 
                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                placeholder="Habit" 
                required>
        </div>

        <div class="mb-4">
            <select 
                name="habit_type" 
                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                required>
                <option value="1">Good</option>
                <option value="2">Bad</option>
            </select>
        </div>

        <div class="flex justify-end">
            <input 
                type="submit" 
                name="submit" 
                class="px-6 py-2 bg-green-400 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 cursor-pointer" 
                value="Add habit">
        </div>
    </form>
</div>

    <div class="grid-container">

    
    <?php include 'done_undone.php'; ?>

    </div>



</section>



<?php include 'components/footer_app.php'; ?>