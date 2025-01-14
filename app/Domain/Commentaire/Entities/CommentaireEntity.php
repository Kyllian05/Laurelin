<?php

namespace App\Domain\Commentaire\Entities;

use App\Domain\Utilisateur\Entities\UtilisateurEntity;

class CommentaireEntity
{
    public readonly int $userId;
    public readonly int $productId;
    public readonly string $content;
    public readonly string $date;
    public readonly string $nom;
    public readonly string $prenom;
    private bool $deletable = false;

    public function __construct(int $userId, int $productId, string $content, string $date, string $nom, string $prenom)
    {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->content = $content;
        $this->date = $date;
        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    public function getDeletable(): bool
    {
        return $this->deletable;
    }

    public function setDeletable(UtilisateurEntity $userEntity): void
    {
        $this->deletable = $this->userId === $userEntity->getId();
    }

    public function serialize(): array
    {
        return [
            'ID_UTILISATEUR' => $this->userId,
            'ID_PRODUIT' => $this->productId,
            'CONTENU' => $this->content,
            'DATE' => $this->date,
            'DELETABLE' => $this->deletable,
            'NOM' => $this->nom,
            'PRENOM' => $this->prenom,
        ];
    }
}
