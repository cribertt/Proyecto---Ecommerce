<template>
    <div>
          <!-- Contenedor de productos -->
          <div class="row">
             <div class="col-lg-4 col-md-6 mb-4" v-for="(producto, p) in props.arrayProductos" :key="p">
                 <div class="card h-100 border-0 shadow-sm">
                    <img class="card-img-top" :src="`http://127.0.0.1:8000/storage/media/${producto.img}`" style="height: 200px; object-fit: contain;">
                     <div class="card-body">
                         <h5 class="card-title">{{ producto.name }}</h5>
                         <p class="card-text">
                             ${{ producto.amount.toLocaleString('es-CL') }} CLP
                         </p>
                         <div class="d-flex justify-content-between align-items-center">
                             <div class="btn-group">
                                 <button href="#" class="btn btn-sm btn-outline-dark rounded-0" @click="mostrarDetalles(producto)">Detalles</button>
                                 <button class="btn btn-sm btn-outline-dark rounded-0" @click="agregarAlCarrito(producto)">Agregar carrito</button>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
          </div>
    </div>

    <!-- MODAL DETALLES -->
    <div class="modal fade" id="detallesModal" tabindex="-1" role="dialog" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="detallesModalLabel">Detalles del Artículo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 ms-6">
                            <img id="imagenModal" class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                        <div class="col-md-6">
                            <p class="h5 mb-3" id="nombreModal"></p>
                            <p id="descripcionModal" class="mb-3"></p>
                            <p id="tallaModal" class="mb-2"></p>
                            <p id="colorModal" class="mb-2"></p>
                            <p id="valorModal"></p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    
</template>
<script setup lang="ts">
import { defineProps } from 'vue';
import { Modal } from "bootstrap"
import eventBus from '../../utils/EventBus.js'; // Ajusta la ruta al archivo EventBus.js según tu estructura

const props = defineProps({
    arrayProductos: Array
})


function mostrarDetalles(producto) {
        var detallesModal = new Modal(document.getElementById('detallesModal'));

        // Limpia las clases de animación al abrir el modal
        detallesModal._element.classList.remove('animacion-fadeIn');

        // Actualiza el modal con los detalles del producto
        var valorFormato = '$' + parseFloat(producto.amount).toLocaleString('en-US') + ' CLP';
        
        document.getElementById('nombreModal').innerText = 'Nombre: ' + producto.name;
        document.getElementById('descripcionModal').innerText = 'Descripcion: ' + producto.description;
      //  document.getElementById('tallaModal').innerText = 'Talla: ' + producto.talla;
       // document.getElementById('colorModal').innerText = 'Color: ' + producto.color;
        document.getElementById('valorModal').innerText = 'Precio: ' + valorFormato;

        // Establece la fuente de la imagen
        document.getElementById('imagenModal').src = `http://127.0.0.1:8000/media/${producto.imagen}`;

        // Animación al abrir el modal
        detallesModal._element.classList.add('animacion-fadeIn');

        // Muestra el modal
        detallesModal.show();

}

function hideModalCart() {
    const modalElement = document.getElementById('detalles');
    const modalInstance = new Modal(modalElement);
    modalInstance.hide();
    
}

eventBus.on('updateModalCart', hideModalCart); 


function agregarAlCarrito(producto) {


    const storedData = localStorage.getItem('productos');
    let listaProductos = [];

    if (storedData) {
        listaProductos = JSON.parse(storedData);
    }

    // Verificar si el producto ya está en el carrito
    const indiceProducto = listaProductos.findIndex(p => p.id === producto.id);

    if (indiceProducto !== -1) {
        listaProductos[indiceProducto].cantidad += 1; // Aumentar la cantidad del producto existente en el carrito
    } else {
        producto.cantidad = 1; // Establecer la cantidad del nuevo producto en 1
        listaProductos.push(producto); // Agregar el nuevo producto al carrito
    }

    // Actualizar el carrito en el almacenamiento local
    localStorage.setItem('productos', JSON.stringify(listaProductos)); 

    eventBus.emit('updateCart'); 
}

</script>