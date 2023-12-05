<?php declare(strict_types=1);

require_once 'page.php';

class Home extends Page
{
    public function generateView(): void
    {
        $title = 'Home';
        $this->generatePageHeader($title);

        echo <<<HTML
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
        HTML;

        $this->generatePageFooter();
    }
}

$homePage = new Home();
$homePage->generateView();
