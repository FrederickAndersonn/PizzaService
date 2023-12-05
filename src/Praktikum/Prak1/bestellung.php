<?php declare(strict_types=1);

require_once 'page.php';

class Bestellung extends Page
{
    // Add any specific attributes or methods for the Bestellung page if needed

    public function generateView(): void
    {
        $title = 'Bestellung';
        $this->generatePageHeader($title);

        echo <<<HTML
        
        <section class="bestellung" id="bestellung">
            <h1 class="bestellung_title">Bestellung</h1>
            <div class="bestellung_form container">
                <div class="bestellung_items">
                    <div class="pizza_infos">
                        <h2>Speisekarte</h2>
                        <div class="speisekarte">
        HTML;

        $pizzas = array(
            "Margarita" => array(
                "price" => 10,
                "image" => "assets/Margherita-9920.jpg.webp"
            ),
            "Pepperoni" => array(
                "price" => 12,
                "image" => "assets/pepperoni-pizza.jpg.webp"
            ),
            "Vegetarian" => array(
                "price" => 11,
                "image" => "assets/Veggie-Pizza-2-of-5-e1691215701129.jpg"
            ),
            "Hawaiian" => array(
                "price" => 13,
                "image" => "assets/hawaiian-pizza-recipe-605894-2.jpg"
            )
        );
        

        foreach ($pizzas as $pizzaName => $pizzaDetails) {
            echo '<div class="pizza_container">';
            echo '<img src="' . $pizzaDetails["image"] . '" alt="' . $pizzaName . '" width="100px">';
            echo '<p>' . $pizzaName . '-' .  $pizzaDetails["price"] . ' $ </p>';
            echo '</div>';
        }

        echo <<<HTML
                        </div>
                    </div>
                    <div class="warenkorb">
                        <h2>Warenkorb</h2>
                        <form action="https://echo.fbi.h-da.de/" class="form" method= "post">
                            <div class="warenkorb_items container">
                                <ul name="selected_items">

                                </ul>
                            </div>
                            <h2 name="total">Total: $<span id="total">0</span></h2>
                            <button class="btn delete_btn" id="deleteBtn" >Delete All</button>
                                <input required type="text" placeholder="Name" class="input" name="user_name">
                                <input required type="email" placeholder="Email" class="input" name="user_email">
                                <input required type="text" placeholder="Addresse Haus Nr." class="input" name="user_address">
                                <input required type="number" placeholder="PLZ" class="input" name="user_plz">
                                <button class="btn" id="bestellenBtn">Bestellen</button>
                        </form>
                    </div>

                </div>
            </div>
        </section>
        <script src="script.js"></script>
        HTML;

        $this->generatePageFooter();
    }
}

$bestellungPage = new Bestellung();
$bestellungPage->generateView();
