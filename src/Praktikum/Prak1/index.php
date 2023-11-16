<?php
echo<<<HTML
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="topnav">
            <a href="index.php">Home</a>
            <a href="bestellung.php">Bestellung</a>
            <a href="kunde.php">Kunde</a>
            <a href="backer.php">Bäcker</a>
            <a href="fahrer.php">Fahrer</a>
        </div>
    </header>
    <section class="home" id="home">
        <div class="intro">
            <img src="" alt="" class="logo">
            <h1 class="home_name">Pizza Gembul</h1>
            <div class="about container">
                <p class="about_content">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellat nisi nulla eaque, sint pariatur odio hic ut vel quod sunt eligendi animi beatae nesciunt nemo odit ipsam illum? Provident, laboriosam.
                </p>
            </div>
            <a href="#bestellung" class="order"></a>
        </div>
    </section>
    <script src="script.js"></script>
</body>
</html>
HTML
?>