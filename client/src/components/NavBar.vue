<template>
    <div>
        <nav class="container-fluid" style="background-color: rgba(20,20,20,1); color: azure;">
            <div style="display: flex; justify-content: center; padding-top: 36px; padding-bottom: 22px;">
                <div class="row" style="margin-top: 10px;">
        
                    <div class="col mx-5">
                        <router-link to="/" class="nav-header">Inicio</router-link>
                       <!--- <a href="productos" class="nav-header">Productos</a> --->
                    </div>

                    <div class="col mx-5">
                        <router-link to="/productos" class="nav-header">Productos</router-link>
                       <!--- <a href="productos" class="nav-header">Productos</a> --->
                    </div>
        
                    <div class="col mx-5">
                        <router-link to="/nosotros" class="nav-header">Nosotros</router-link>
                    </div>

                </div>
                <ComponentCarrito/>
                
            </div>
        </nav>
    </div>
</template>
<script setup lang="ts">
import { onMounted, ref } from 'vue';
import ComponentCarrito from '../components/Carrito.vue'

const storedData = ref(false)
const productsList = ref([])

function getProducts() {
    storedData.value = localStorage.getItem('productos')    

    if(storedData.value != '[]') {
        
        productsList.value = JSON.parse(storedData.value)    
        
    }else{
        storedData.value = false
    }
}


</script>