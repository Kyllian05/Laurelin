<template>
    <div id="fieldWrapper">
        <div v-for="field in fields" class="fieldDiv">
        <p class="font-body-s">{{ field.name }}</p>
        <input :type="field.type ? field.type : 'text'" :id="'field_'+encodeURI(field.name)" required>
    </div>
    </div>
    <div id="bottomFormWrapper">
        <div v-for="checkbox in checkBoxs" class="checkBoxDiv">
            <input type="checkbox" :id="'check_'+encodeURI(checkbox)">
            <p class="font-normal-12">{{checkbox}}</p>
        </div>
        <a v-for="link in links" :href="link.link" class="font-normal-12" id="linksWrapper">{{ link.text }}</a>
    </div>
    <ButtonSubmit :button-text="buttonText" @submit-clicked="sendData"></ButtonSubmit>
</template>

<script setup>
import { defineProps } from 'vue';
import ButtonSubmit from "./ButtonSubmit.vue";

let props = defineProps({
    "fields":Array[Object],
    "buttonText":String,
    "checkBoxs":Array,
    "links":Array[Object],
    "dest":String
})

async function sendData(){
    let body = {}

    for(let i = 0;i<props.fields.length;i++){
        if(!document.getElementById("field_"+encodeURI(props.fields[i].name)).checkValidity()){
            alert("Formulaire invalide")
            return
        }
        body[props.fields[i].name] = document.getElementById("field_"+encodeURI(props.fields[i].name)).value
    }

    for(let i = 0;i<props.checkBoxs.length;i++){
        body[props.checkBoxs[i]] = document.getElementById("check_"+encodeURI(props.checkBoxs[i])).checked
    }

    const response = await fetch(props.dest,{
        method:"POST",
        body: JSON.stringify(body),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type":"application/json"
        }
    })
    if(response.status != 200){
        const reader = response.body.getReader()
        const text = new TextDecoder().decode((await reader.read()).value)
        alert(text)
    }
}
</script>

<style scoped>
    #bottomFormWrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
    }
    #linksWrapper:hover {
        text-decoration: underline;
    }
    #linksWrapper {
        cursor: pointer;
        text-decoration: none;
        color: black;
    }
    input[type="checkbox"]{
        accent-color: #000000;
        cursor: pointer;
    }
    .checkBoxDiv{
        display: flex;
        flex-direction: row;
        gap: 8px;
        align-items: center;
    }
    #fieldWrapper{
        display: flex;
        flex-direction: column;
        gap: 2vh;
    }
    .fieldDiv{
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .fieldDiv input{
        border-radius: 10px;
        border: solid 1px black;
        height: 5vh;
        width: 98%;
        padding-left: 2%;
    }
</style>
