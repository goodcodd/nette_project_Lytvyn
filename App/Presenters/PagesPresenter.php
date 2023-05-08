<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;

final class PagesPresenter extends Nette\Application\UI\Presenter
{
    public function renderParliament(): void // this function is called when the page is loaded
    {
        $this->getTemplate()->title = 'Parliament'; // set the title
        $this->getTemplate()->message = 'Hello, it\'s Parliament page, rendered by PagesPresenter'; // set the message
    }

    public function renderPassport(): void // this function is called when the page is loaded
    {
        $this->getTemplate()->title = 'Passport'; // set the title
        $this->getTemplate()->message = 'Hello, it\'s Passport page, rendered by PassportPresenter'; // set the message
    }
}
