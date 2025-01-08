
<template>
    <Header current-page="CheckOut"></Header>
        <div id="Page">
            <div id="Title" class="font-subtitle-16">Commande</div>
            <div id="DonneeCommande">
                <div id="infoWrapper">
                    <div id="info" class="font-subtitle-16">1 - Informations Personnelles</div>
                    <div id="contenuInfo">
                        <div id="texteInfo" class="font-body-m"> Pour poursuivre votre commande veuillez vous identifiez</div>
                        <button class="font-body-l">Se connecter</button>
                        <button class="font-body-l">S'inscrire</button>
                    </div>
                </div>

                <div id="adresseWrapper">
                    <div id="adresse" class="font-subtitle-16">2 - Adresse de livraison</div>
                    <div id="contenuAdresse">
                        <!---
                        <div id="adresseChoiceWrapper">
                            <button :class="{autChoiceActive :  authMethod === 'domicile'}" @click="changeAuthMethod()" class="font-subtitle-16"></button>
                            <button :class="{autChoiceActive :  authMethod === ''}" @click="changeAuthMethod()" class="font-subtitle-16"></button>
                        </div>
                        <Form v-if="authMethod === 'login'" :fields="[{'name':inputs['login']['fields'][0],type:'email'},{'name':inputs['login']['fields'][1],type:'password'}]" button-text="Connexion" :check-boxs="[inputs['login']['checkBoxs'][0]]" :texts="['Mot de passe oublié']" dest="/auth/login"  @textClicked="handleClick" succeed-message="Vous êtes connecté"></Form>

                        <Form v-else :fields="[{'name':inputs['register']['fields'][0]},{'name':inputs['register']['fields'][1]},{'name':inputs['register']['fields'][2],type:'email'},{'name':inputs['register']['fields'][3],'type':'password'}]" :check-boxs="[inputs['register']['checkBoxs'][0]]" button-text="S'inscrire" dest="/auth/register" succeed-message="Vous êtes inscrit"></Form>
                        --->
                        <div id="newAdresse" class="font-subtitle-16">Nouvelle adresse</div>
                        <div id="Retirer" class="font-subtitle-16">retirer en magasin</div>
                        <select id="selectVille" class="font-subtitle-16">
                            <option value="">Choisir une ville</option>
                            <!---mettre les villes de la base de dounées-->
                        </select>
                    </div>
                </div>

                <div id="livraisonWrapper">
                    <div id="livraison" class="font-subtitle-16">3 - Options de Livraison</div>
                </div>

                <div id="paiementWrapper">
                    <div id="paiement" class="font-subtitle-16">4 - Options de paiement</div>
                </div>

            </div>

            <div id="Recap">
                <div id="RecapTitle" class="font-subtitle-16">Récapitulatif de commande</div>
                <div id="modif" class="font-body-m">Modifier</div>
                <div id="nbArticles" class="font-body-s">articles</div>
                <div id="produitComander" class="font-subtitle-16">
                    <!----met ton vfor laaa pooool-->
                </div>
                <div id="sousTot">
                    <div id="soustotal" class="font-subtitle-16">sous-total</div>
                    <div id="tva" class="font-subtitle-16">tva</div>
                </div>
                <div id="Tot">
                    <div id="total" class="font-subtitle-16">total</div>
                </div>

            </div>
        </div>
    <Footer></Footer>
</template>



<script setup>

import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
import Form from "./Components/Form.vue";
</script>


<style scoped>

#Page{
    display: flex;
    flex-wrap: wrap;
    margin-top: 119px;
    justify-content: center;
    margin-bottom: 100px;
}

#Title{
    width: 94%;
    padding: 40px;
    text-align: center;
    border-top: 1px solid black;
    border-bottom: 1px solid black;

}

#DonneeCommande {
    flex: 1 1 40%;
    margin-left: 3%;
    padding-right: 3%;
}

#infoWrapper {
    padding: 35px 0 35px 20px;
    border-bottom: 1px solid black;
}

#contenuInfo {
    display: none;
    text-align: center;
    padding-top: 20px;
}

#contenuInfo button {
    margin: 10px;
    width: 200px;
    height: 50px;
    border-radius: 10px;
    border: 1px solid black;
    background-color: black;
    color: white;
    transition: 0.5s ease;
}

#contenuInfo button:hover {
    background-color: white;
    color: black;
}

#adresseWrapper {
    padding: 35px 0 35px 20px;
    border-bottom: 1px solid black;
}

#contenuAdresse {
    display: none;
}

#livraison {
    padding: 35px 0 35px 20px;
    border-bottom: 1px solid black;
}

#paiement {
    padding: 35px 0 35px 20px;
    border-bottom: 1px solid black;
}

#Recap{
    flex: 1 1 20%;
    padding: 25px;
    margin-right: 3%;
}

#produitComander{
    border-top: 1px solid black;
    margin-top: 30px;
}

#Tot {
    border-top: 1px solid black;
    width: 80%;
    margin: 30px auto 0;
}

#sousTot{
    padding: 10px 0 0 15px;
}

#total {
    padding: 10px 0 0 15px;
}



</style>
