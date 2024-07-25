<?php
declare(strict_types=1);


namespace App\Main\Controllers;

use App\Main\Forms\LoginForm;
use App\Main\Models\Users;
use App\Main\Services\AuthenticationService;
use Phalcon\Encryption\Security;

class AuthController extends BaseController {
    public function indexAction(): void {
        $this->tag->title()->set('Log In');

        $form = new LoginForm();

        if ($this->isDevEnv) {
            // Set demo user credentials
            /** @see InitTask */
            $form->get('email')->setDefault('demo@demo.test');
            $form->get('password')->setDefault('demo');
        }

        $this->view->form = $form;
    }

    public function loginAction(): void {
        if (!$this->request->isPost()) {
            $this->redirectToLoginPage();
            return;
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        if (!$email || !$password) {
            $this->flashSession->error('Please enter email and password');
            $this->redirectToLoginPage();
            return;
        }

        /** @var AuthenticationService $authService */
        $authService = $this->container->get('auth');
        $user = $authService->getUser($email, $password);
        if (!$user) {
            $this->flashSession->error('Incorrect credentials, try again');
            $this->redirectToLoginPage();
        }

        // Success, populate session and redirect to main page
        $this->session->set('user', [
            'id'   => $user->id,
            'full_name' => $user->full_name,
        ]);
        $this->flashSession->success('Welcome ' . $user->full_name);
        $this->response->redirect('/');
    }

    public function logoutAction(): void {
        $this->session->remove('user');
        $this->flashSession->success('See you soon!');
        $this->redirectToLoginPage();
    }

    private function redirectToLoginPage(): void {
        $this->response->redirect('/auth');
    }
}
