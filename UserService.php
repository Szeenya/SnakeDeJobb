<?php
require_once "User.php";

class UserService
{
    static function register(User $user)
    {
        $name = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();

        if ($name && $email && $password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $user->setPassword($hashedPassword);
            $modelResult = User::registerUser($name, $email, $hashedPassword);

            if ($modelResult) {
                return [
                    'status' => 200,
                    'message' => 'User Registered',
                ];
            } else {
                return [
                    'status' => 500,
                    'message' => 'Registration failed',
                ];
            }
        } else {
            return [
                'status' => 417,
                'message' => 'Missing Credentials',
            ];
        }
    }
}
?>
