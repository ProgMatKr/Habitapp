<?php 


$motivational_quotes = [
    "Good habits formed at youth make all the difference. - Aristotle",
    "The secret of getting ahead is getting started. - Mark Twain",
    "Don't watch the clock; do what it does. Keep going. - Sam Levenson",
    "The future belongs to those who believe in the beauty of their dreams. - Eleanor Roosevelt",
    "The best way to predict the future is to create it. - Peter Drucker",
    "You are never too old to set another goal or to dream a new dream. - C.S. Lewis",
    "It always seems impossible until it's done. - Nelson Mandela",
    "Believe you can and you're halfway there. - Theodore Roosevelt",
    "Your limitation—it's only your imagination. - Unknown",
    "Push yourself, because no one else is going to do it for you. - Unknown",
    "Success is not final, failure is not fatal: It is the courage to continue that counts. - Winston Churchill",
    "The harder you work for something, the greater you'll feel when you achieve it. - Unknown",
    "Dream bigger. Do bigger. - Unknown",
    "Don't stop when you're tired. Stop when you're done. - Unknown",
    "Wake up with determination. Go to bed with satisfaction. - Unknown",
    "Do something today that your future self will thank you for. - Unknown",
    "Little things make big days. - Unknown",
    "It's going to be hard, but hard does not mean impossible. - Unknown",
    "Don't wait for opportunity. Create it. - Unknown",
    "Sometimes we're tested not to show our weaknesses, but to discover our strengths. - Unknown"
];


?>



<div class="user_welcome">
    
        <div class="container">
    
            <div class="row">
    
                <div class="col-md-6">
    
                    <h1 class="text-2xl">Hello, <?php echo htmlspecialchars($_SESSION["user"]); ?>!</h1>
                    
                    <p><?php echo $motivational_quotes[array_rand($motivational_quotes)];?></p>
    
                </div>
    
            </div>
    
        </div>


</div>