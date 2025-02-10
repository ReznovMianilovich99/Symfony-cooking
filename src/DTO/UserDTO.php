<?php

namespace App\DTO;

use App\Entity\User;

class UserDTO
{
    public ?int $id = null;
    public ?string $email = null;
    public ?string $roles = null;

    public function __construct(User $user)
    {
        // Initialisation des propriétés de base
        $this->id = $user->getId();
        $this->email = $user->getEmail();
        $this->roles = $user->getRoles();
    }
}
?>
