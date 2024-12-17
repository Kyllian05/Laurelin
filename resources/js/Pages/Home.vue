<template>
    <p>Voici le props passé : {{ test }}</p>
    <p>{{ compteur }}</p>
    <button @click="compteur++">Incrémenter</button>
    <p v-if="urlData">Voici la valeur passé en url : {{ urlData }}</p>
    <p v-else>Vous n'avez pas passé de valeur dans l'url</p>
    <div id="form">
        <Form :fields="[{'name':'Adresse e-mail'},{'name':'Mot de passe',type:'password'}]" button-text="Connexion" :check-boxs="['Se souvenir de moi']" :links="[{'text':'Mot de passe oublié ?','link':'youtube.com'}]"></Form>
    </div>
    <Footer></Footer>
</template>

<script setup>
import {ref} from "vue"
import Footer from "./Components/Footer.vue"
import Form from "./Components/Form.vue";

defineProps({
    test : String,
    urlData : String
})

let compteur = ref(0)

</script>

<style scoped>
#form{
    width: 30vw;
    margin-left: 50%;
    transform: translateX(-50%);
    margin-bottom: 10vh;
    margin-top: 10vh;
}
</style>