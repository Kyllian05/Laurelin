<template>
    <div id="fieldWrapper">
        <div v-for="field in fields" class="fieldDiv">
        <p>{{ field.name }}</p>
        <input :type="field.type ? field.type : 'text'">
    </div>
    </div>
    <div id="checkBoxWrapper">
        <div v-for="checkbox in checkBoxs" class="checkBoxDiv">
            <input type="checkbox">
            <p>{{checkbox}}</p>
        </div>
    </div>
    <div id="linksWrapper">
        <a v-for="link in links" :href="link.link">{{ link.text }}</a>
    </div>
    <button v-if="buttonText">{{ buttonText }}</button>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
    "fields":Array[Object],
    "buttonText":String,
    "checkBoxs":Array,
    "links":Array[Object]
})
</script>

<style scoped>
    #linksWrapper a:hover{
        text-decoration: underline;
    }
    #linksWrapper a{
        cursor: pointer;
        text-decoration: none;
        color: black;
    }
    #linksWrapper{
        margin-top: 2vh;
        float: right;
    }
    input[type="checkbox"]{
        accent-color: #000000;
        cursor: pointer;
    }
    #checkBoxWrapper{
        margin-top: 2vh;
        float: left;
    }
    .checkBoxDiv{
        display: flex;
        flex-direction: row;
        gap: 0.25vw;
        align-items: center;
    }
    button{
        background-color: black;
        width: 100%;
        color: white;
        border: none;
        border-radius: 10px;
        height: 5vh;
        margin-top: 4vh;
    }
    #fieldWrapper{
        display: flex;
        flex-direction: column;
        gap: 2vh;
    }
    .fieldDiv{
        display: flex;
        flex-direction: column;
        gap: 1vh;
    }
    .fieldDiv input{
        border-radius: 10px;
        border: solid 1px black;
        height: 5vh;
        width: 98%;
        padding-left: 2%;
    }
    p, a{
        font-family: "Tenor Sans", serif;
        margin: 0px;
        font-size: 12px;;
    }
</style>