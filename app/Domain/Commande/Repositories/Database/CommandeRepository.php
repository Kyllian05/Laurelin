<?php

namespace App\Domain\Commande\Repositories\Database;

use App\Domain\Adresse\Entities\AdresseEntity;
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

        $commandesIds = [];
        $adressesId = [];
        for($i = 0; $i < count($commadesModel); $i++){
            $commandesIds[] = $commadesModel[$i]["ID"];
            $adressesId[] = $commadesModel[$i]["ID_ADRESSE"];
        }

        $produitsCommandes = $this->produitCommandeRepository->findByCommandesIds($commandesIds);
        $adresses = $this->adresseRepository->findByIds($adressesId);

        $produits = [];

        foreach($produitsCommandes as $produitCommande){
            $produits[$produitCommande->getCommandeID()][] = $produitCommande;
        }

        $commandeAdresse = [];

        foreach($adresses as $adresse){
            $commandeAdresse[$adresse->id] = $adresse;
        }

        $commandes = [];

        foreach ($commadesModel as $commande) {
            // TODO : enlever la condition c'est pour les magasins
            if (!is_null($commande->ID_ADRESSE)) {
                $commandes[] = CommandeEntity::commandeFactory(
                    $commande->ID,
                    $commande->DATE,
                    $produits[$commande->ID],
                    $commande->ETAT,
                    $commande->MODE_LIVRAISON,
                    $commandeAdresse[$commande->ID_ADRESSE]
                );
            }
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

    public function updateLivraisonDomicile(CommandeEntity $commandeEntity, AdresseEntity $adresseEntity): void
    {
        $commandeEntity->modifyLivraison(Livraison::Domicile);
        $commandeEntity->modifyAdresse($adresseEntity);
        CommandeModel::where("ID", $commandeEntity->getId())->update([
            "MODE_LIVRAISON" => "domicile",
            "ID_ADRESSE" => $commandeEntity->getAdresse()->id,
        ]);
    }

    public function updateLivraisonMagasin(CommandeEntity $commandeEntity): void
    {
        throw new \Exception("TODO : not implemented");
    }
}
