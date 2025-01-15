<?php

namespace App\Domain\Commentaire\Repositories;

use App\Domain\Commentaire\Entities\CommentaireEntity;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;
use App\Domain\Utilisateur\Repositories\UtilisateurRepository;
use App\Models\Commentaire as CommentaireModel;

class CommentaireRepository
{
    public function  __construct(private UtilisateurRepository $utilisateurRepository) {}

    public function findByProduct(ProduitEntity $produit): array
    {
        $commentaireModel = CommentaireModel::where('ID_PRODUIT', $produit->id)->get();

        $allCommentaires = [];
        foreach ($commentaireModel as $commentaire) {
            $user = $this->utilisateurRepository->findByID($commentaire->ID_UTILISATEUR);

            $allCommentaires[] = new CommentaireEntity(
                $commentaire->ID_UTILISATEUR,
                $commentaire->ID_PRODUIT,
                $commentaire->CONTENU,
                $commentaire->DATE,
                $user->getNom(),
                $user->getPrenom(),
            );
        }

        return $allCommentaires;
    }

    public function create(UtilisateurEntity $utilisateurEntity, int $productId, string $contenu): CommentaireEntity
    {
        $commentaireModel = CommentaireModel::create([
            "CONTENU"=>$contenu,
            "ID_UTILISATEUR"=>$utilisateurEntity->getId(),
            "ID_PRODUIT"=>$productId,
            "DATE"=>date('Y-m-d', time())
        ]);

        $commentaire = new CommentaireEntity(
            $commentaireModel->ID_UTILISATEUR,
            $commentaireModel->ID_PRODUIT,
            $commentaireModel->CONTENU,
            $commentaireModel->DATE,
            $utilisateurEntity->getNom(),
            $utilisateurEntity->getPrenom(),
        );
        $commentaire->setDeletable($utilisateurEntity);
        return $commentaire;
    }

    public function delete(UtilisateurEntity $utilisateurEntity, int $productId): void
    {
        CommentaireModel::where([
            "ID_PRODUIT"=>$productId,
            "ID_UTILISATEUR"=>$utilisateurEntity->getId(),
        ])->delete();
    }
}
