<?php declare(strict_types=1);

require_once 'page.php';

class Fahrer extends Page
{
    public function generateView(): void
    {
        $title = 'Fahrer';
        $this->generatePageHeader($title);

        echo <<<HTML
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
        HTML;

        $this->generatePageFooter();
    }
}

$fahrerPage = new Fahrer();
$fahrerPage->generateView();
