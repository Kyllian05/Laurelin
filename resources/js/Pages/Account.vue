<template>
    <Error :message="errorMesage" v-if="errorMesage != ''" @click="errorMesage = ''"></Error>
    <Header currentPage="account"></Header>
    <div v-if="ajoutAdresseState" id="ajoutAdresseWrapper">
        <div id="ajoutAdresseContent">
            <span class="material-symbols-rounded" @click="ajoutAdresseState = false" id="ajoutAdresseClose">close</span>
                <div id="formAdresse">
                    <p id="titre" class="font-body-s">AJOUTER UNE NOUVELLE ADRESSE</p>
                    <div id="elementAdresse">
                        <Field name="Numéro" @input="value => updateNewAdresseValue('Numéro',value)"></Field>
                        <Field name="Nom de rue" @input="value => updateNewAdresseValue('Nom de rue',value)"></Field>
                        <Field name="Code Postale" @input="value => searchVille(value)"></Field>
                        <select id="selectCodePost" v-if="villesSuggest.length > 0" v-model="villeChoice">
                            <option v-for="ville in villesSuggest" :value="{'ID':ville.ID}">{{ ville.NOM }} {{ ville.CODE_POSTAL }}</option>
                        </select>

                        <ButtonSubmit buttonText="Valider" @click="newAdresseClicked"></ButtonSubmit>
                </div>
            </div>
        </div>
    </div>
    <div id="mainWrapper">
        <div id="navWrapper">
            <div id="elementsNav" v-for="nav in Object.keys(navConv)" :class="dynamicPage == nav ? 'currentNav' : ''" @click="dynamicPage = nav">
                <span class="material-symbols-rounded">{{ navConv[nav].icon }}</span>
                <p id="pageTitre" class="font-body-s">{{ navConv[nav].text }}</p>
            </div>
        </div>
        <div id="contentWrapper">
            <p id="pageInfo" class="font-body-s">{{ navConv[dynamicPage].text  }}</p>
            <hr style="margin-bottom: 2vh">
            <div v-if="dynamicPage == 'info'">
                <div id="formWrapper1">
                    <Form :fields="infoFields[0]" :check-boxs="[]" buttonText="Valider les modifications" :displayColumn="true" dest="/updateInfo" succeed-message="Les modifications ont bien étés effectuées" @error-occured="message => errorMesage = message"></Form>
                </div>
                <div id="logoutWrapper">
                    <button id="buttonDeco" class="deco font-body-l" @click="logout"> Déconnexion </button>
                </div>
            </div>
            <div v-else-if="dynamicPage == 'commandes'" id="commandsWrapper">
                <div v-for="(commande,index) in commandes" class="commandWrapper" :class="index%2==1 ? 'grayBackground' :''">
                    <p class="font-body-s" style="margin-bottom: 2vh">Commande du {{commande.DATE}}</p>
                    <p class="font-body-s" style="margin-bottom: 2vh">Produits :</p>
                    <ul>

                        <li id="prodCom" v-for="produit in commande.PRODUITS">
                            <div v-if="produit.TAILLE !== 0">
                                <p class="font-body-s">{{ produit.PRODUIT.NOM }} {{ produit.QUANTITE > 1 ? 'x'+produit.QUANTITE : '' }} - {{produit.TAILLE}} cm</p>
                            </div>
                            <div v-else>
                                <p class="font-body-s">{{ produit.PRODUIT.NOM }} {{ produit.QUANTITE > 1 ? 'x'+produit.QUANTITE : '' }}</p>
                            </div>
                        </li>
                    </ul>
                    <div class="commandSideWrapper">
                        <button class="font-body-s" :class="commande.ETAT === 'Terminée' ? 'commandFinished' : ''">{{commande.ETAT}}</button>
                        <p class="font-body-s">TOTAL : {{ formatPrix(getCommandeSum(commande)) }}€</p>
                    </div>
                    <span class="material-symbols-rounded commandesDownArrow" @click="currentCommandDetail != commande.ID ? currentCommandDetail = commande.ID : currentCommandDetail = null" :style="currentCommandDetail != commande.ID ? 'rotate : 90deg' : 'rotate : 270deg'">
                        arrow_forward_ios
                    </span>
                    <div v-if="currentCommandDetail == commande.ID" class="commandsDetails font-body-l">
                        <p>Méthode de livraison : {{ commande.LIVRAISON }}</p>
                        <p v-if="commande.LIVRAISON == 'domicile'">{{commande.ADRESSE.NUM_RUE}} {{commande.ADRESSE.NOM_RUE}} {{commande.ADRESSE.VILLE.NOM}} {{commande.ADRESSE.VILLE.CODE_POSTAL}}</p>
                        <p v-else>{{commande.ADRESSE.ADRESSE}} {{commande.ADRESSE.VILLE.NOM}} {{commande.ADRESSE.VILLE.CODE_POSTAL}}</p>
                    </div>
                </div>
            </div>
            <div v-else-if="dynamicPage == 'favoris'" id="favorisWrapper">
                <div v-if="favorisImagesCount < dynamicFavoris.length">
                    Loading
                </div>
                <div v-show="favorisImagesCount >= dynamicFavoris.length" v-for="favori in dynamicFavoris" class="favorisWrappers">
                    <div style="position: relative;width: fit-content" class="imageWrapper">
                        <div id="image">
                            <a :href="'/produit/'+favori.ID" target="_blank">
                                <img :src="favori.IMAGES[0]" class="favorisImage" @load="favorisImageLoaded()">
                            </a>
                            <span class="material-symbols-rounded favoriteRemoveSymbol displayHover" @click="supprimerFavoris(favori.ID)">
                            remove
                            </span>
                            <div class="buttonAcheter displayHover">
                                <ButtonAcheter white-border="false" :id="favori.ID"></ButtonAcheter>
                            </div>
                        </div>
                        <p>{{ favori.NOM }}</p>
                        <p>{{ formatPrix(favori.PRIX) }}€</p>
                    </div>
                </div>
            </div>
            <div v-else>
                <button id="addAdressButton" @click="ajoutAdresseState = true">
                    <span class="material-symbols-rounded">
                        add
                    </span>
                    <p class="font-body-s">AJouter une adresse</p>
                </button>
                <div v-for="adresse in dynamicAdresse" class="adresseWrappers">
                    <p>{{ adresse.NUM_RUE }} {{ adresse.NOM_RUE }}, {{ adresse.VILLE.CODE_POSTAL }} {{ adresse.VILLE.NOM }}</p>
                    <div>
                        <span class="material-symbols-rounded garbageIcon" @click="deleteAdresse(adresse.ID)">
                            delete
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Footer></Footer>
</template>

<script setup>
import {defineProps, ref, watch} from "vue"
import Form from "./Components/Form.vue"
import Header from "./Components/Header.vue"
import Footer from "./Components/Footer.vue"
import ButtonAcheter from "./Components/ButtonAcheter.vue";
import Field from "./Components/Field.vue";
import ButtonSubmit from "./Components/ButtonSubmit.vue";
import Error from "./Components/Error.vue";

let props = defineProps(
    {
        "page":String,
        "info":Object,
        "commandes":Object,
        "favoris" : Object,
        "adresses" : Array,
    })

    console.log(props.commandes)

    const favorisImagesCount = ref(0)

    const dynamicFavoris = ref(props.favoris)

    const dynamicPage = ref(props.page)

    const dynamicAdresse = ref(props.adresses)

    const ajoutAdresseState = ref(false)

    const villesSuggest = ref([]);

    const villeChoice = ref()

    const ajoutAdresseData = ref({
        "Numéro":"",
        "Nom de rue":""
    })

    const currentCommandDetail = ref(null)

    const errorMesage = ref("")

    watch(dynamicPage, async ()=>{
        currentCommandDetail.value = null
    })

    watch(ajoutAdresseState,async (newState)=>{
        if(newState){
            document.body.style.overflowY = "hidden"
        }else{
            document.body.style.overflowY = "scroll"
        }
    })

    let navConv = {
        "info" : {text:"Informations personnelles",icon:"person"},
        "commandes" : {text: "Mes commandes",icon:"shopping_cart"},
        "favoris" : {text:"Mes produits favoris",icon:"favorite"},
        "adresses" : {text:"Mes adresses",icon:"home"}
    }

    let infoFields = [[],[]]

    for(let i = 0;i<3;i++){
        infoFields[0].push({"name":Object.keys(props.info)[i],"value":props.info[Object.keys(props.info)[i]]})
    }

    infoFields[1].push({"name":Object.keys(props.info)[3],"value":props.info[Object.keys(props.info)[3]],"required":false})

    function updateNewAdresseValue(field,value){
        ajoutAdresseData.value[field] = value
    }

    function newAdresseClicked(){
        fetch("/adresse/ajout",{
            method : "POST",
            body : JSON.stringify({
                "Numéro" : ajoutAdresseData.value["Numéro"],
                "Nom de rue" : ajoutAdresseData.value["Nom de rue"],
                "Ville" : villeChoice.value
            }),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type":"application/json"
            },
        }).then(async response => {
            dynamicAdresse.value.push(await response.json())
            ajoutAdresseState.value = false
        })
    }

    function searchVille(codePostal){
        if(codePostal === ""){
            villesSuggest.value = []
        }else{
            fetch("/adresse/getVilles/"+codePostal,{
                method:"GET",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Content-Type":"application/json"
                },
            }).then( async response =>{
                villesSuggest.value = await response.json()
            })
        }
    }

    function deleteAdresse(id){
        fetch("/adresse/supprimer",{
            method:"POST",
            body: JSON.stringify({"ID":id}),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type":"application/json"
            },
        }).then(async response =>{
            if(response.status === 200){
                dynamicAdresse.value = dynamicAdresse.value.filter((adresse) => adresse.ID != id)
            }else{
                const reader = response.body.getReader()
                errorMesage.value = new TextDecoder().decode((await reader.read()).value)
            }
        })
    }

    function favorisImageLoaded(){
        favorisImagesCount.value++
    }

    function getCommandeSum(commande){
        let sum = 0
        commande.PRODUITS.forEach((produit)=>{
            sum += produit.PRIX * produit.QUANTITE
        })
        return sum
    }

    function supprimerFavoris(id){
        fetch("/supprimerFavoris",{
            method : "POST",
            body : JSON.stringify({
                produit : id
            }),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type":"application/json"
            },
        }).then(async response => {
            if(response.status == 200){
                dynamicFavoris.value = dynamicFavoris.value.filter((favori) => favori["ID"] != id)
            }
        })
    }

    function logout() {
        fetch("/account", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                if (response.ok) {
                    window.location.href = '/auth';
                } else {
                    console.error('Erreur lors de la déconnexion');
                }
            })
            .catch(error => console.error('Erreur réseau :', error));
    }

    const formatPrix = (prix) => {
        return new Intl.NumberFormat("fr-FR", {
            style: "decimal",
            maximumFractionDigits: 0, // Pas de décimales
        }).format(prix);
    };

</script>

<style scoped>
    .commandsDetails{
        margin-top: 2vh;
    }
    .commandesDownArrow:hover{
        background-color: lightgray;
    }
    .commandesDownArrow{
        position: absolute;
        font-size: 2vw;
        cursor: pointer;
        right: 4.5vw;
        bottom: 1vh;
        padding: 0.5vw;
        transition-duration: .5s;
        border-radius: 50%;
    }
    .displayHover{
        opacity: 0;
        transition-duration: .25s;
    }
    .buttonAcheter{
        position: absolute;
        bottom: -25px;
        left: 0;
        margin-left: 50%;
        transform: translateX(-50%);
        width: 90%;
    }
    #titre {
        display: flex;
        font-size: 19px;
        letter-spacing: 1px;
        margin-bottom: 5vh;
        justify-content: center;
        width: 80%;
    }

    #ajoutAdresseClose:hover{
        background-color: rgba(0,0,0,20%);
        border-radius: 50%;
    }
    #ajoutAdresseClose{
        cursor: pointer;
        font-size: 30px;
        width: fit-content;
        min-width: 30px;
        min-height: 30px;
        margin: 5px 0 0 5px;
    }
    #ajoutAdresseContent{
        display: flex;
        flex-direction: column;
        width: 50vw;
        max-width: 770px;
        height: fit-content;
        margin-top: 50vh;
        margin-left: 50vw;
        padding-bottom: 30px;
        transform: translate(-50%, -50%);
        background-color: white;
        border-radius: 20px;
    }
    #ajoutAdresseWrapper{
        display: flex;
        position: absolute;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0,0,0,20%);
        z-index: 1000;
    }

    #formAdresse {
        display: flex;
        flex-direction: column;
        width: 100%;
        align-items: center;
        padding-top: 30px;
    }

    #selectCodePost {
        margin-left: 50%;
        transform: translateX(-50%);
        border-bottom: solid 1px black !important;
        padding: 2px;
        justify-content: space-around;
        background: none;
        border: none;
        cursor: pointer;
        margin-top: 10px;
        width: 230px;
    }

    #elementAdresse {
        text-align: left;
        width: 85%;
    }


    #txtCodePostal {
        justify-content: left;
    }

    #inputCodePostal {
        border-radius: 10px;
        border: solid 1px black;
        height: 5vh;
        width: 100%;
    }

    #addAdressButton p{
        letter-spacing: 1px;
    }
    #addAdressButton{
        background-color: black;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 15px;
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 1vw;
        gap: 1vw;
    }
    .adresseWrappers div span{
        padding: 0.5vw;
        border-radius: 50%;
        box-shadow: 0 0 8px rgba(0,0,0,20%);
        cursor: pointer;
    }
    .adresseWrappers div{
        display: flex;
        flex-direction: row;
        gap: 1vw;
        height: fit-content;
    }
    .adresseWrappers{
        display: flex;
        flex-direction: row;
        position: relative;
        justify-content: space-between;
        height: 10vh;
        align-items: center;
        border-bottom: solid 1px black;
    }
    .garbageIcon{
        color: rgb(200,0,0);
        background-color: rgba(255,0,0,0.1);

    }
    .favoriteRemoveSymbol{
        position: absolute;
        right: 0.5vw;
        top: 0.5vw;
        background-color: white;
        border-radius: 50%;
        box-shadow: 0 0 8px rgba(0,0,0,20%);
        cursor: pointer;

    }

    .favorisWrappers div:hover .displayHover{
        opacity: 1;
    }
    .favorisWrappers div:hover img{
        filter: brightness(70%);
    }

    .favorisWrappers{
        text-align: center;
    }

    #favorisWrapper{
        display: grid;
        gap: 10px;
        grid-template-columns: repeat(3,1fr);
        height: max-content;
    }


    .favorisImage{
        width: 100%;
        cursor: pointer;
        transition-duration: .25s;
        aspect-ratio: 1/1;
        object-fit: cover;
        object-position: top;
    }

    #image {
        position: relative;
    }

    .commandSideWrapper p{
        margin-top: 2vh;
    }
    .commandSideWrapper{
        position: absolute;
        right: 2vw;
        top: 2vw;
        text-align: right;
    }
    .commandFinished{
        color: white;
        background-color: black!important;
    }
    .commandWrapper button{
        border: solid 1px black;
        border-radius: 25px;
        padding-top: 1vh;
        padding-bottom: 1vh;
        padding-left: 2.5vw;
        padding-right: 2.5vw;
        background-color: transparent;
        font-size: 16px;
    }
    .commandWrapper{
        padding: 20px;
        position: relative;
        min-height: 20vh;
    }
    .grayBackground{
        background-color: #eee;
    }
    .commandWrapper ul{
        margin-left: 2vw;
        display: flex;
        flex-direction: column;
        gap: 1vh;
    }
    #commandsWrapper p{
        letter-spacing: 1px;
        font-size: 15px;
        font-weight: lighter;
    }

    #prodCom {
        max-width: 70%;
    }

    #commandsWrapper {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    #pageInfo{
        font-size: 18px;
        margin-bottom: 1.5vh;
        letter-spacing: 2px;
    }
    #navWrapper div:hover{
        background-color: black;
        color: white;
    }
    #navWrapper div p{
        width: max-content;
        text-align: center;
    }
    #mainWrapper{
        display: flex;
        flex-direction: row;
        gap: 10vw;
        width: fit-content;
        padding-left: 10vw;
        padding-top: 119px;
        height: fit-content;
        min-height: 100vh;
    }
    .currentNav{
        background-color: black;
        color: white;
    }
    #navWrapper{
        display: flex;
        flex-direction: column;
        cursor: pointer;
        border: solid 1px gray;
        width: fit-content;
        height: fit-content;
    }
    #navWrapper div{
        height: 4vh;
        padding-left: 2vw;
        padding-right: 2vw;
        text-align: center;
        align-content: center;
        cursor: pointer;
        transition-duration: .2s;
        display: flex;
        justify-content: left;
        gap: 1vw;
        align-items: center;
    }
    #contentWrapper{
        width: 50vw;
        margin-bottom: 5vh;
        height: fit-content;
    }
    #formWrapper2{
        margin-top: 5vh;
    }

    #buttonDeco {
        background-color: black;
        width: 60%;
        color: white;
        margin-left: 50%;
        transform: translate(-50%);
        border: none;
        border-radius: 10px;
        margin-top: 40px;
        padding: 18px 0;
        cursor: pointer;
        transition: background-color .2s;
    }

    #buttonDeco:hover {
        background-color: #3a3a3a;
    }

    #logoutWrapper {
        border-top: 1px solid black;
        margin-top: 20px;
    }

    @media (max-width: 800px) {
        #mainWrapper {
            display: flex;
            flex-direction: column;
            width: 100%;
            padding-left: 0;
            align-items: center;
            text-align: center;
        }

        #navWrapper {
            display: grid;
            grid-template-columns: repeat(4, auto);
            grid-template-rows: 1fr;
            border: 0px;
            width: 80%;
            align-items: center;
        }


        #elementsNav {
            border-bottom: 1px solid black;
        }

        #elementsNav span {
            margin-left: 50%;
            transform: translate(-50%);
        }

        #pageTitre {
            display: none;
        }

        #contentWrapper {
            width: 80%;
        }

        #commandsWrapper {
            display: flex;
            flex-direction: column;
            gap: 40px;
            text-align: left;
        }


    }

    @media (max-width: 550px) {
        #favorisWrapper {
            display: flex;
            flex-direction: column;
        }
    }

    @media (max-width: 630px), (min-width: 800px) and (max-width: 1100px) {
        .commandWrapper {
            display: flex;
            flex-direction: column;
        }

        .commandSideWrapper {
            display: flex;
            flex-direction: column ;
            position: relative;
            left: 50%;
            transform: translate(-50%);
            align-items: center;
        }

        .commandSideWrapper p{
            text-align: center;
        }

        .commandSideWrapper button {
            width: 80%;
        }
    }

    @media (max-width: 1000px) {
        #ajoutAdresseContent {
            width: 80vh;
            height: fit-content;
            margin-left: 10%;
            margin-top: 50vh;
            transform: translate(-6%, -50%);
        }
    }



</style>
