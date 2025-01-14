<template>
    <div id="wrapper">
        <p class="font-body-s">{{ name }}</p>
        <input :type="type ? type : 'text'" v-model="inputValue" :required="required!=null">
    </div>
</template>

<script setup>
import {defineProps, ref, watch} from "vue";

    let props = defineProps(["name","type","value","required"])

    let emits = defineEmits(["input"])

    let inputValue = ref(props.value)

    watch(inputValue, async (newValue)=>{
        emits("input",newValue)
    })

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
        width: 100%;
    }
</style>
