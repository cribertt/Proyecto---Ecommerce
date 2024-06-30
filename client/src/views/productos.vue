<template>
<div class="container mx-6" style="margin-top: 35px;">
    
    <div style="display: flex; justify-content: end; margin-right: 18px;">
        <form action="productos" method="get" style="margin-bottom: 35px;">
            <input @input="buscarProductos" id="buscarProductos" class="form-control-sm" name="search" placeholder="Buscar productos" style="height: 45px;width: 16rem; border-radius: 12px;" >
        </form>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <ComponentCategoria :arrayCategorias="arrayCategorias" />
            </div>
            <div class="col-md-9">
                <ComponentProducto :arrayProductos="arrayProductosFiltrados" />
            </div>
        </div>
    </div>
</div>
</template>
<script setup lang="ts">
import { onMounted, ref } from 'vue';
import ComponentCategoria from '../components/Productos/Categorias.vue'
import ComponentProducto from '../components/Productos/Productos.vue'
import eventBus from '../utils/EventBus.js'


const arrayProductos = ref([])
const arrayCategorias = ref([])

const arrayProductosFiltrados = ref([])

function actualizarProductos(productos) {
  arrayProductosFiltrados.value = productos;
}

eventBus.on('productosFiltrados', actualizarProductos)

async function obtenerArticulos() {
    const response = await fetch('http://127.0.0.1:8000/api/v1/productos', {
        method: 'get',
        mode: 'cors',
        credentials: 'include'
    })

    
    if (response.ok) {
        const data = await response.json()

        arrayProductos.value = data.articulos;
        arrayProductosFiltrados.value = data.articulos;
        arrayCategorias.value = data.categorias;        
    }else {
        console.log('Error en la respuesta');
        
    }
    
    
}

function buscarProductos() {
        let textoBusqueda = document.getElementById("buscarProductos").value.toLowerCase();
        
        arrayProductosFiltrados.value = arrayProductos.value.filter(producto => {
            return producto.name.toLowerCase().includes(textoBusqueda);
        });
    }

onMounted(async () => {
    await obtenerArticulos()

})
</script>