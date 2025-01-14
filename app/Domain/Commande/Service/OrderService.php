<?php

namespace App\Domain\Commande\Service;

use App\Domain\Adresse\Entities\AdresseEntity;
use App\Domain\Adresse\Entities\AdresseMagasin;
use App\Domain\Adresse\Entities\AdresseUtilisateur;
use App\Domain\Commande\Entities\CommandeEntity;
use App\Domain\Commande\Repositories\Database\CommandeRepository as DBCommandeRepository;
use App\Domain\Shared\Livraison;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;

class OrderService
{
    public function __construct(
        private DBCommandeRepository $commandeRepository
    ) {}

    public function toOrder(CommandeEntity $commandeEntity): void
    {
        $this->commandeRepository->toOrder($commandeEntity);
    }

    public function order(CommandeEntity $commandeEntity, string $livraison, AdresseEntity $adresseEntity, UtilisateurEntity $utilisateurEntity): void
    {
        if ($livraison == "domicile" && $adresseEntity instanceof AdresseUtilisateur)
        {
            // Vérifier que l'adresse appartient à l'utilisateur
            $valid = false;
            foreach ($utilisateurEntity->getAdresses() as $adresse) {
                if ($adresse->id == $adresseEntity->id) {
                    $valid = true;
                    break;
                }
            }
            if (!$valid) {
                throw new \InvalidArgumentException("L'adresse n'appartient pas à l'utilisateur");
            }
            $this->commandeRepository->updateLivraisonDomicile($commandeEntity, $adresseEntity);
        } elseif ($livraison == "magasin" && $adresseEntity instanceof AdresseMagasin) {
            $this->commandeRepository->updateLivraisonMagasin($commandeEntity, $adresseEntity);
        } else {
            throw new \InvalidArgumentException("Unexpected type of livraison or address is invalid");
        }

        $this->commandeRepository->updatePrices($commandeEntity);

        $this->commandeRepository->toClosed($commandeEntity);
    }
}
