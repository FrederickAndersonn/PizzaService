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
    <section class="backer" id="backer">
        <h1>Baecker</h1>
        <form action="https://echo.fbi.h-da.de/" method="post">
            <ul class="customer-list">
                <li class="customer-item">
                    <span class="customer-name">Customer 1:</span>
                    <div class="order-status">
                        <span class="customer-name">Salami:</span>
                        <input type="radio" name="order1" value="ordered" id="order1-ordered">
                        <label for="order1-ordered" class="order-status-label">Ordered</label>
                        <input type="radio" name="order1" value="inoven" id="order1-inoven">
                        <label for="order1-inoven" class="order-status-label">In the Oven</label>
                        <input type="radio" name="order1" value="ready" id="order1-ready">
                        <label for="order1-ready" class="order-status-label">Ready</label>
                        <br>
                        <span class="customer-name">Margerita</span>
                        <input type="radio" name="order2" value="ordered" id="order2-">
                        <label for="order2-ordered" class="order-status-label">Ordered</label>
                        <input type="radio" name="order2" value="inoven" id="order2-inoven2">
                        <label for="order2-inoven" class="order-status-label">In the Oven</label>
                        <input type="radio" name="order2" value="ready" id="order2-ready2">
                        <label for="order2-ready" class="order-status-label">Ready</label>
                    </div>
                    <button class="btn submit_status_backer">Submit</button>
                </li>
            </ul>
        </form>
    </section>
</body>

</html>