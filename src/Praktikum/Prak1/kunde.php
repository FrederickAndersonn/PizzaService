<?php declare(strict_types=1);

require_once 'page.php';

class Kunde extends Page
{
    public function generateView(): void
    {
        $title = 'Kunde';
        $this->generatePageHeader($title);

        echo <<<HTML
        <section class="kunde" id="kunde">
            <h1>Kunde</h1>
            <div class="lieferstatus">
                <h3>Lieferstatus</h3>
                <p>Salami: Bestellt</p>
                <p>Margerita: In Oven</p>
            </div>
        </section>
        HTML;

        $this->generatePageFooter();
    }
}

$kundePage = new Kunde();
$kundePage->generateView();
