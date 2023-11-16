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
    <section class="fahrer" id="fahrer">
        <h1>Fahrer</h1>
        <form action="https://echo.fbi.h-da.de/" method="post">
            <ul class="customer-list">
                <li class="customer-item">
                    <div class="order-status">
                        <span class="fahrer_name">Abdul</span>
                        <br>
                        <span class="delivery_info">Memestrasse 19 - 15$</span>
                        <br>
                        <input type="radio" name="fahrer_status" value="rdf" id="fahrer_status-rfd">
                        <label for="order1-rdf" class="order-status-label">Ready for Delivery</label>
                        <input type="radio" name="fahrer_status" value="od" id="fahrer_status-od">
                        <label for="fahrer_status-od" class="order-status-label">On Delivery</label>
                        <input type="radio" name="fahrer_status" value="ready" id="fahrer_status-d">
                        <label for="order1-deliveres" class="order-status-label">Delivered</label>
                        <br>
                    </div>
                    <button class="btn submit_status_fahrer">Submit</button>
                </li>
            </ul>
        </form>
    </section>
</body>

</html>
HTML
?>