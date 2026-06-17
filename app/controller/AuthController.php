<?php

declare(strict_types=1);

final class AuthController extends BaseController
{
    public function login(): void
    {
        // F1 : verifie le couple login/mot de passe puis ouvre la session.
        $errors = [];
        $login = '';

        if ($this->isPost()) {
            $login = $this->postString('login');
            $password = $this->postString('password');

            if ($login === '' || $password === '') {
                $errors[] = 'Le login et le mot de passe sont obligatoires.';
            } else {
                $user = $this->users->authenticate($login, $password);
                if ($user) {
                    session_regenerate_id(true);
                    $_SESSION['login_id'] = (int) $user['id'];
                    set_flash('success', 'Connexion réussie. Bienvenue ' . $user['nom'] . ' ' . $user['prenom'] . '.');
                    redirect_to('home');
                }

                $errors[] = 'Login ou mot de passe incorrect.';
            }
        }

        $this->render('auth/login.php', [
            'title' => 'Login',
            'errors' => $errors,
            'login' => $login,
        ]);
    }

    public function logout(): void
    {
        // F2 : remet login_id a -1 pour revenir a l'etat non connecte.
        $_SESSION['login_id'] = -1;
        session_regenerate_id(true);
        set_flash('success', 'Déconnexion effectuée.');
        redirect_to('home');
    }
}
