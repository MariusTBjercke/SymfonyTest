<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="string", length=255)
     */
    private string $username;

    /**
     * @Column(type="string", length=255)
     */
    private string $password;

    /**
     * @Column(type="string", length=255)
     */
    private string $email;

    /**
     * @OneToMany(targetEntity="\App\Entity\Forum\Post", mappedBy="author")
     */
    private $forumPosts;

    /**
     * @OneToMany(targetEntity="\App\Entity\Article", mappedBy="author")
     */
    private $articlePosts;

    /**
     * @Column(type="boolean", name="logged_in", options={"default": false})
     */
    private bool $loggedIn;

    /**
     * @Column(type="boolean", name="is_admin", options={"default": false})
     */
    private bool $isAdmin;

    /**
     * @Column(type="string", length=255, name="last_activity", nullable=true)
     */
    private string $lastActivity;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForumPosts() {
        return $this->forumPosts;
    }

    /**
     * @param mixed $forumPosts
     * @return User
     */
    public function setForumPosts($forumPosts) {
        $this->forumPosts = $forumPosts;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticlePosts() {
        return $this->articlePosts;
    }

    /**
     * @param mixed $articlePosts
     * @return User
     */
    public function setArticlePosts($articlePosts) {
        $this->articlePosts = $articlePosts;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool {
        return $this->loggedIn;
    }

    /**
     * @param bool $loggedIn
     * @return User
     */
    public function setLoggedIn(bool $loggedIn): User {
        $this->loggedIn = $loggedIn;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     * @return User
     */
    public function setIsAdmin(bool $isAdmin): User {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastActivity(): string {
        return $this->lastActivity;
    }

    /**
     * @param string $lastActivity
     * @return User
     */
    public function setLastActivity(string $lastActivity): User {
        $this->lastActivity = $lastActivity;
        return $this;
    }
}
