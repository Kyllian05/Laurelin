<template>
    <div id="wrapper">
        <p class="font-body-s">{{ name }}</p>
        <input :type="type ? type : 'text'" :value="value" :required="required!=null">
    </div>
</template>

<script setup>
    import {defineProps} from "vue";

    let props = defineProps(["name","type","value","required"])
</script>

<style scoped>
    #wrapper{
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    input{
        border-radius: 10px;
        border: solid 1px black;
        height: 5vh;
        width: 98%;
        padding-left: 2%;
    }
</style>
