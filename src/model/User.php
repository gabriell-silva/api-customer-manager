<?php


class User
{
    private int $id;
    private string $username;
    private string $password;
    private string $token;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): int
    {
        return $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): string
    {
        return $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): string
    {
        return $this->password = $password;
    }

    public function getToken(): string
    {
        return $this->password;
    }

    public function setToken(string $token): string
    {
        return $this->token = $token;
    }
}