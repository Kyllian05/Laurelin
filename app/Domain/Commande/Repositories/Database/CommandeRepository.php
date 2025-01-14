<?php

namespace App\Domain\Commande\Repositories\Database;

use App\Domain\Adresse\Entities\AdresseEntity;
use App\Domain\Adresse\Entities\AdresseMagasin;
use App\Domain\Adresse\Entities\AdresseUtilisateur;
use App\Domain\Adresse\Repositories\AdresseRepository;
use App\Domain\Commande\Entities\CommandeEntity;
use App\Domain\Commande\Entities\ProduitCommandeEntity;
use App\Domain\Commande\Repositories\CommandeRepositoryInterface;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Produit\Repositories\ImageRepository;
use App\Domain\Shared\Livraison;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;
use \App\Models\Commande as CommandeModel;

class CommandeRepository implements CommandeRepositoryInterface
{
    public function __construct(
        private ProduitCommandeRepository $produitCommandeRepository,
        private AdresseRepository $adresseRepository,
        private ImageRepository $imageRepository,
    ){}

    public function findByUser(UtilisateurEntity $user) : array
    {
        $commadesModel = CommandeModel::where(["ID_UTILISATEUR" => $user->getId()])->where("ETAT","!=","Panier")->get()->sortByDesc("DATE");

        $commandesIds = $commadesModel->pluck("ID")->toArray();
        // Utilisation de filter pour enlever les ID nulls
        $adressesId = $commadesModel->pluck("ID_ADRESSE")->filter()->unique()->toArray();
        $adressesMagasinId = $commadesModel->pluck("ID_MAGASIN")->filter()->unique()->toArray();

        $produitsCommandes = $this->produitCommandeRepository->findByCommandesIds($commandesIds);
        $adresses = $this->adresseRepository->findByIds($adressesId);
        $adresseMagasins = $this->adresseRepository->findMagasinByIds($adressesMagasinId);

        $produitsIndexes = [];
        foreach($produitsCommandes as $produitCommande){
            $produitsIndexes[$produitCommande->getCommandeID()][] = $produitCommande;
        }

        $adresseIndexees = [];
        foreach($adresses as $adresse){
            $adresseIndexees[$adresse->id] = $adresse;
        }

        $adresseMagasinIndexees = [];
        foreach($adresseMagasins as $adresseMagasin){
            $adresseMagasinIndexees[$adresseMagasin->id] = $adresseMagasin;
        }

        $commandes = [];
        foreach ($commadesModel as $commande) {
            if (!is_null($commande->ID_ADRESSE) && is_null($commande->ID_MAGASIN)) {
                $finalAdresse = $adresseIndexees[$commande->ID_ADRESSE];
            } else if (is_null($commande->ID_ADRESSE) && !is_null($commande->ID_MAGASIN)) {
                $finalAdresse = $adresseMagasinIndexees[$commande->ID_MAGASIN];
            } else {
                throw new \Exception("La commande ne possède pas d'adresse valide");
            }
            $commandes[] = CommandeEntity::commandeFactory(
                $commande->ID,
                $commande->DATE,
                $produitsIndexes[$commande->ID],
                $commande->ETAT,
                $commande->MODE_LIVRAISON,
                $finalAdresse
            );
        }
        return $commandes;
    }

    private function createCart(UtilisateurEntity $user) : CommandeEntity
    {
        $panierModel = CommandeModel::create([
        "DATE" => date ('Y-m-d H:i:s', time()),
        "ETAT" => "Panier",
        "MODE_LIVRAISON"=>null,
        "ID_UTILISATEUR" => $user->getId(),
        "ID_ADRESSE" => null]);

        return CommandeEntity::commandeFactory(
            $panierModel->ID,
            $panierModel->DATE,
            [],
            "Panier"
        );
    }

    public function getCart(?UtilisateurEntity $user) : CommandeEntity
    {
        if (is_null($user)) {
            throw new \InvalidArgumentException("L'utilisateur doit être connecté pour persister son panier");
        }
        $panierModel = CommandeModel::where(["ID_UTILISATEUR" => $user->getId(),"ETAT"=>"Panier"])->first();
        if ($panierModel) {
            return CommandeEntity::commandeFactory(
                $panierModel->ID,
                $panierModel->DATE,
                $this->produitCommandeRepository->findByCommandeId($panierModel->ID, true),
                $panierModel->ETAT
            );
        }
        return $this->createCart($user);
    }

    public function addProduct(CommandeEntity $commande, ProduitEntity $produitEntity, int $taille, ?int $prix): void
    {
        $this->imageRepository->getAllProductImages($produitEntity);

        $prodCommande = new ProduitCommandeEntity(
            $taille,
            1,
            $prix,
            $produitEntity,
            $commande->getId()
        );

        $commande->addProduct($prodCommande);
        $this->produitCommandeRepository->create($commande, $prodCommande);
    }

    public function modifyQuantity(CommandeEntity $commande, ProduitCommandeEntity $produitCommandeEntity, int $quantity): void
    {
        $commande->modifyQuantity($produitCommandeEntity, $quantity);
        $this->produitCommandeRepository->modifyQuantity($commande, $produitCommandeEntity, $quantity);
    }

    public function removeProduct(CommandeEntity $commande, ProduitCommandeEntity $produitCommandeEntity): void
    {
        $commande->removeProduct($produitCommandeEntity);
        $this->produitCommandeRepository->delete($commande, $produitCommandeEntity);
    }

    // Spécifique au DBCommandeRepository

    public function toOrder(CommandeEntity $commandeEntity): void
    {
        $commandeEntity->toOrder();
        CommandeModel::where("ID", $commandeEntity->getId())->update(["ETAT"=>"Validée"]);
    }

    public function toClosed(CommandeEntity $commandeEntity): void
    {
        $commandeEntity->toClosed();
        CommandeModel::where("ID", $commandeEntity->getId())->update(["ETAT"=>"Terminée"]);
    }

    public function updatePrices(CommandeEntity $commandeEntity): void
    {
        foreach ($commandeEntity->getProducts() as $product) {
            $this->produitCommandeRepository->updatePrice($product, $commandeEntity);
        }
    }

    public function updateLivraisonDomicile(CommandeEntity $commandeEntity, AdresseUtilisateur $adresseEntity): void
    {
        $commandeEntity->modifyLivraison(Livraison::Domicile);
        $commandeEntity->modifyAdresse($adresseEntity);
        CommandeModel::where("ID", $commandeEntity->getId())->update([
            "MODE_LIVRAISON" => "domicile",
            "ID_ADRESSE" => $commandeEntity->getAdresse()->id,
        ]);
    }

    public function updateLivraisonMagasin(CommandeEntity $commandeEntity, AdresseMagasin $adresseMagasin): void
    {
        $commandeEntity->modifyLivraison(Livraison::Magasin);
        $commandeEntity->modifyAdresse($adresseMagasin);
        CommandeModel::where("ID", $commandeEntity->getId())->update([
            "MODE_LIVRAISON" => "magasin",
            "ID_MAGASIN" => $commandeEntity->getAdresse()->id,
        ]);
    }
}
