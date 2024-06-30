<template>
          <!-- Contenedor de categorías -->
        <div class="categoria-container">
             <div id="categoria-title">
                 <h5>Categorías</h5>
             </div>
             <div style="padding: 18px 20px;">
                <ul class="list-group">
                    <button class="btn btn-sm" @click="limpiarFiltro">Todos</button>
                   <button v-for="(categoria, c) in props.arrayCategorias" :key="c"  class="btn btn-sm mt-3" @click="filtrado(categoria.id)">{{ categoria.name }}</button>
                </ul>
             </div>
        </div>
        
</template>
<script setup lang="ts">
import { defineProps, ref, onMounted } from 'vue';
import eventBus from '../../utils/EventBus.js'
const props = defineProps({
    arrayCategorias: Array
})

const storedData = ref(false)
const productsList = ref([])

const arrayProductos = ref([])
const arrayProductosFiltrados = ref([])


async function limpiarFiltro() {
  await obtenerArticulos()

  arrayProductosFiltrados.value = arrayProductos.value
  eventBus.emit('productosFiltrados', arrayProductosFiltrados.value);
}

async function obtenerArticulos() {
    const response = await fetch('http://127.0.0.1:8000/api/v1/productos', {
        method: 'get',
        mode: 'cors',
        credentials: 'include'
    })

    
    if (response.ok) {
        const data = await response.json()
        arrayProductos.value = data.articulos;
    }else {
        console.log('Error en la respuesta');
        
    }
    
    
}
async function filtrado(id) {
    await obtenerArticulos(); // Espera a obtener los productos

    arrayProductosFiltrados.value = arrayProductos.value.filter(producto => {
        console.log(producto);
        
        return producto.id_category === id;
    });

    // Emite un evento con los productos filtrados
     eventBus.emit('productosFiltrados', arrayProductosFiltrados.value);
}



</script>

