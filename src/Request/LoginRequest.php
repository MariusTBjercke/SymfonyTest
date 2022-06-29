<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest {
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=255)
     * @var string
     */
    public string $username;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    public string $password;
}
