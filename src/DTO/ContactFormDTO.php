<?php

declare(strict_types=1);

namespace App\DTO;

class ContactFormDTO
{
    public function __construct(
        private string $name = '',
        private string $email = '',
        private string $message = '',
        private string $service = 'maxou@gmail.com',
    ) {}

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function setService(string $service): void
    {
        $this->service = $service;
    }

    public function getService(): string
    {
        return $this->service;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMessage(): string
    {
        return $this->message;
    }


}