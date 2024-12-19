<template>
    <div id="fieldWrapper">
        <div v-for="field in fields" class="fieldDiv">
        <p class="font-body-s">{{ field.name }}</p>
        <input :type="field.type ? field.type : 'text'" :id="'field_'+encodeURI(field.name)">
    </div>
    </div>
    <div id="bottomFormWrapper">
        <div v-for="checkbox in checkBoxs" class="checkBoxDiv">
            <input type="checkbox">
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

function sendData(){
    let body = {}

    props.fields.forEach(field => {
        body[field.name] = document.getElementById("field_"+encodeURI(field.name)).value
    });

    fetch(props.dest,{
        method:"POST",
        body: JSON.stringify(body),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type":"application/json"
        }
    })
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
