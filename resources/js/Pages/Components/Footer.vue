<template>
    <div id="firstWrapper">
        <div v-for="element in Object.keys(content)">
            <h1 class="fontNewsreader">{{ element }}</h1>
            <div v-for="element2 in Object.keys(content[element])">
                <h2 class="fontNewsreader">{{ element2 }}</h2>
                <p v-for="element3 in Object.keys(content[element][element2])" class="fontNewsreader">{{ element3 }}</p>
            </div>
        </div>
    </div>
    <div id="secondWrapper">
        <h1 class="fontNewsreader">Notre association</h1>
        <img src="/public/images/goofyHouse.png">
    </div>
</template>

<script setup>
    const content = {
        "Laurelin":{
            "Nos Produits":{
                "Bagues":undefined,//il faudra mettre les liens de redirection
                "Bracelet":undefined,
                "Collier":undefined,
                "Parures":undefined,
                "Boucles d'Oreilles":undefined
            },
            "A Propos de nous":{
                "Créateur":undefined,
                "Créations":undefined,
                "Durabilité":undefined
            },
            "Contact":{
                "Nous contacter":undefined,
                "Questions fréquentes":undefined
            }
        },
        "Legal et Confidentialité":{
            "Mention légale":{
                "Condition d’utilisation":undefined,
                "Condition de vente":undefined,
                "Politique de confidentialité":undefined,
                "Politique de cookies":undefined,
                "Cookies center":undefined
            },
            "Support":{
                "Brochures":undefined,
                "Accesibilité":undefined,
                "Effectuer un signalement":undefined
            }
        },
        "Canaux Principaux" : {

        }
    }
</script>

<style scoped>
#secondWrapper img{
    position: absolute;
    z-index: -1;
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: opacity(0.7)
}
#secondWrapper h1{
    color: black;
    position: relative;
    margin-left: 7.5vw;
    font-weight: 500;
    font-size: 40px;
}
#secondWrapper{
    position: relative;
    aspect-ratio: 16/5;
    height: fit-content;
    width: 100vw;
    display: flex;
    align-items: center;
}
.fontNewsreader{
    font-family: "Newsreader", serif;
}
#firstWrapper{
    display: flex;
    flex-direction: row;
    justify-content: space-around;
}
</style>