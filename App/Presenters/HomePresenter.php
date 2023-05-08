<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private readonly Nette\Database\Explorer $database
    ) {
        parent::__construct();
    }

    public function renderIndex(): void // this function is called when the page is loaded
    {
        $this->getTemplate()->title = 'Home';
        $this->getTemplate()->message = 'Hello, it\'s Home page, rendered by HomePresenter'; // set the message

        $this->getTemplate()->users = $this->database
            ->table('users')
            ->order('user_id ASC')
            ->limit(20);
    }

    /**
     * @return void
     */
    public function renderAbout(): void
    {
        $this
            ->getTemplate()
            ->title = 'About';
        $this
            ->getTemplate()
            ->message = 'Hello, it\'s About page, rendered by HomePresenter';
    }

    public function createComponentUploadForm(): Form
    {
        $form = new Form();

        $form->addUpload('file', 'Выберите файл:')
            ->setRequired('Выберите файл для загрузки.');

        $form->addSubmit('submit', 'Загрузить');

        $form->onSuccess[] = [$this, 'uploadFormSucceeded'];

        return $form;
    }

    /**
     * @throws AbortException
     */
    public function uploadFormSucceeded(Form $form, \stdClass $values): void
    {
        /** @var FileUpload $file */
        $file = $values->file;

        if ($file->isOk()) {
            $file->move('./uploads/' . $file->name);

            $this->flashMessage('Файл успешно загружен', 'success');
        } else {
            $this->flashMessage('Произошла ошибка при загрузке файла', 'danger');
        }

        $this->redirect('this');
    }

    /**
     * @return void
     */
    public function renderContacts(): void
    {
        $this->getTemplate()->title = 'Contacts';
        $this->getTemplate()->message = 'Hello, it\'s Contacts page, rendered by HomePresenter';
    }
}
