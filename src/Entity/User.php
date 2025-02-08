<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['users.list', 'users.show'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['users.create','users.list', 'users.show'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['users.create','users.list', 'users.show'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['users.list', 'users.show'])]
    private ?string $roles = null;

    #[ORM\OneToOne(mappedBy: 'iduser', cascade: ['persist', 'remove'])]
    private ?Commande $iduser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?string
    {
        $roles = $this->roles;
        if ($roles == null)
        {
            $roles = 'ROLE_USER';
        }
        return $roles;
    }

    public function setRoles(String $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getIduser(): ?Commande
    {
        return $this->iduser;
    }

    public function setIduser(Commande $iduser): static
    {
        // set the owning side of the relation if necessary
        if ($iduser->getIduser() !== $this) 
        {
            $iduser->setIduser($this);
        }
        $this->iduser = $iduser;

        return $this;
    }
}
