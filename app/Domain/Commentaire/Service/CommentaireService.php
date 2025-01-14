<?php

namespace App\Domain\Commentaire\Service;

use App\Domain\Commentaire\Entities\CommentaireEntity;
use App\Domain\Commentaire\Repositories\CommentaireRepository;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;

class CommentaireService
{
    public function __construct(private CommentaireRepository $commentaireRepository) {}

    public function findByProduct(ProduitEntity $produit): array
    {
        return $this->commentaireRepository->findByProduct($produit);
    }

    public function create(UtilisateurEntity $utilisateurEntity, int $productId, string $contenu): CommentaireEntity
    {
        return $this->commentaireRepository->create($utilisateurEntity, $productId, $contenu);
    }

    public function delete(UtilisateurEntity $utilisateurEntity, int $productId): void
    {
        $this->commentaireRepository->delete($utilisateurEntity, $productId);
    }
}
