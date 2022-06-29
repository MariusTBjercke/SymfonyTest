<?php

use App\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security) {
    $security->passwordHasher(PasswordAuthenticatedUserInterface::class, 'auto');

    $security->firewall('dev');

    $security->firewall('main');

    $security
        ->provider('app_user_provider')
        ->entity()
        ->class(User::class)
        ->property('username');

    $security->passwordHasher(PasswordAuthenticatedUserInterface::class)->algorithm('auto');
};
