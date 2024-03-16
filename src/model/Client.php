<?php


class Client
{
    private int $id;
    private string $name;
    private string $dateOfBirth;
    private string $documentCpf;
    private string $documentRg;
    private string $phoneNumber;
    private string $address;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): int
    {
        return $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): string
    {
        return $this->name = $name;
    }

    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(string $date): string
    {
        return $this->dateOfBirth = $date;
    }


    public function getDocumentCpf(): string
    {
        return $this->documentCpf;
    }

    public function setDocumentCpf(string $documentCpf): string
    {
        return $this->documentCpf = $documentCpf;
    }

    public function getDocumentRg(): string
    {
        return $this->documentRg;
    }

    public function setDocumentRg(string $documentRg): string
    {
        return $this->documentRg = $documentRg;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): string
    {
        return $this->phoneNumber = $phoneNumber;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): string
    {
        return $this->address = $address;
    }
}