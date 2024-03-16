<?php

class Address
{
    private int $id;
    private string $street;

    function getId(): int
    {
        return $this->id;
    }

    function setId(int $id): int
    {
        return $this->id = $id;
    }

    function getStreet(): string
    {
        return $this->street;
    }


    function setStreet(string $street): string
    {
        return $this->street = $street;
    }

}