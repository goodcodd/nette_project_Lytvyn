<?php

namespace App\Presenters;

use Nette;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use stdClass;

final class SignPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private readonly Nette\Database\Explorer $database,
    ) {
        parent::__construct();
    }
    /**
     * @return Form
     */
    protected function createComponentSignInForm(): Form
    {
        $form = new Form;

        $form->addText('login', "Ім'я користувача:")
            ->addRule(Nette\Forms\Form::FILLED, 'Будь ласка, введіть ваше ім\'я.')
            ->setRequired('Будь ласка, введіть ваше ім\'я.')
            ->addRule(
                Nette\Forms\Form::MIN_LENGTH,
                'Ім\'я користувача має містити %d або більше символів.',
                3
            ) // Username must contain %d or more characters.
            ->addRule(
                Nette\Forms\Form::MAX_LENGTH,
                'Ім\'я користувача має містити %d або менше символів.',
                20
            ); // Username must contain %d or less characters.

        $form->addPassword('password', 'Пароль:')
            ->addRule(Nette\Forms\Form::FILLED, 'Будь ласка, введіть ваш пароль.')
            ->setRequired('Будь ласка, введіть ваш пароль.');

        $form->addSubmit('send', 'Увійти');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];

        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $data
     * @return void
     * @throws AbortException
     */
    public function signInFormSucceeded(Form $form, stdClass $data): void
    {
        try {
            $this->getUser()->login($data->login, $data->password);
            $this->flashMessage('Ви успішно авторизувались.');
            $this->redirect('Home:index');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError("Неправильний логін чи пароль.");
            $this->flashMessage($e->getMessage());
        }
    }

    /**
     * @return Form
     */
    protected function createComponentSignUpForm(): Form
    {
        $form = new Form;
        // Username
        $form->addText('username', 'Ім\'я користувача:')
            ->setRequired('Будь ласка, введіть ваше им\'я.')
            ->addRule(
                Nette\Forms\Form::MIN_LENGTH,
                'Ім\'я користувача має містити %d або більше символів.',
                3
            ) // Username must contain %d or more characters.
            ->addRule(
                Nette\Forms\Form::MAX_LENGTH,
                'Ім\'я користувача має містити %d або менше символів.',
                20
            ) // Username must contain %d or less characters.
            ->addRule(
                Nette\Forms\Form::PATTERN,
                'Ім\'я користувача має містити лише прописні літери, цифри, тире або нижнє підкреслення.',
                '^[A-z0-9_-]+$'
            ); // Username must contain only lowercase letters, numbers, dashes or underscores.


        /** Пароль має містити %d або більше символів.
         * Пароль має містити %d або менше символів.
         * Пароль має містити прописні літери, цифри, тире або нижнє підкреслення.
         */
        $form->addPassword('password', 'Пароль:')
            ->setRequired('Будь ласка, введіть ваш пароль.')
            ->addRule(
                Nette\Forms\Form::MIN_LENGTH,
                'Пароль має містити %d або більше символів.',
                8
            ) // Password must contain %d or more characters.
            ->addRule(
                Nette\Forms\Form::MAX_LENGTH,
                'Пароль має містити %d або менше символів.',
                20
            ); // Password must contain %d or less characters.


        // Password confirmation
        $form->addPassword('password2', 'Повторіть пароль:')
            ->setRequired('Будь ласка, введіть ваш пароль.')
            ->addRule(
                Nette\Forms\Form::EQUAL,
                'Паролі не співпадають.',
                $form['password']
            );  // порівнюємо з полем password

        // E-mail
        $form->addText('email', 'E-mail:')
            ->setRequired('Будь ласка, введіть ваш e-mail.')
            ->addRule(
                Nette\Forms\Form::EMAIL,
                'Неправильний формат e-mail.'
            ); // Invalid e-mail format.

        // Submit button
        $form->addSubmit('send', 'Зареєструватись');

        // Call method signInFormSucceeded() on success
        $form->onSuccess[] = [$this, 'signUpFormSucceeded'];
        return $form;
    }

    /**
     * @throws AbortException
     * @throws AuthenticationException
     */
    public function signUpFormSucceeded(Form $form, $data): void
    {
        /**
         * Логін має бути унікальним
         * пошта має бути унікальною
         */
        $result = $this->database->table('users')
            ->where('login', $data->username)
            ->whereOr([
                'login > ?' => $data->username,
                'email = ?' => $data->email,
            ])
            ->fetch();

        if (!$result) {
            $this->flashMessage('Користувач успішно створений.');
            $this->database->table('users')->insert([
                'login' => $data->username,
                'password' => password_hash($data->password, PASSWORD_DEFAULT),
                'email' => $data->email,
            ]);

            $this->getUser()->login($data->username, $data->password);
            $this->redirect('Home:index');
        } else {
            $form->addError('Користувач з таким іменем вже існує.');
        }
    }


    /**
     * @throws AbortException
     */
    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->flashMessage('Вы вышли.');
        $this->redirect('Home:index');
    }
}
