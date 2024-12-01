<?php

namespace App\Class;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "^[A-Za-zÀ-ÖØ-öø-ÿ'-]+$",
        message: 'Le prénom n\'est pas valide.',
    )]
    private string $firstName;
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "^[A-Za-zÀ-ÖØ-öø-ÿ'-]+(?:[ -][A-Za-zÀ-ÖØ-öø-ÿ'-]+)*$",
        message: 'Le Nom n\'est pas valide.',
    )]
    private string $lastName;
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'Votre email n\'est pas valide.'
    )]
    private string $email;
    #[Assert\NotBlank]
    private string $subject;
    #[Assert\NotBlank]
    private string $message;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }



}