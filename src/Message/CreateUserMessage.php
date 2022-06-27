<?php

namespace App\Message;

final class CreateUserMessage {
    private string $username;
    private string $password;
    private string $email;
    private bool $loggedIn;
    private bool $isAdmin;

    public function __construct(string $name, string $password, string $email, bool $loggedIn, bool $isAdmin) {
        $this->username = $name;
        $this->password = $password;
        $this->email = $email;
        $this->loggedIn = $loggedIn;
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool {
        return $this->loggedIn;
    }

    /**
     * @param bool $loggedIn
     */
    public function setLoggedIn(bool $loggedIn): void {
        $this->loggedIn = $loggedIn;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin): void {
        $this->isAdmin = $isAdmin;
    }
}
