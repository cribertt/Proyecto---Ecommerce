<template>
    <div>
        <button type="button" class="carrito-btn position-relative" data-bs-toggle="modal" data-bs-target="#carritoModal" >
            <img src="@/assets/img/carrito-compras.png" alt="Carrito" style="width: 50px;">
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ productsList.length }}</span>
        </button>
    </div>
    <div class="modal fade" id="carritoModal" tabindex="-1" aria-labelledby="carritoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carritoModalLabel">Carrito de Compras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body overflow-auto">
                 
                    <div class="alert alert-primary" role="alert">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" colspan="6" class="text-center">CARRITO</th>
                                    </tr>
                                    <tr>
                                        <th scope="col">NOMBRE</th>
                                        <th scope="col">PRECIO</th>
                                        <th scope="col">CANTIDAD</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Talla</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyCarrito">
                                     <tr v-if="storedData" id="articulo-{{key}}" v-for="(producto, p) in productsList" :key="p">
                                         <td>{{ producto.name }}</td>
                                         <td>{{ producto.cantidad > 1 ? (producto.cantidad * producto.amount).toLocaleString('es-CL'): producto.amount.toLocaleString('es-CL') }}</td>
                                         <td>{{ producto.cantidad  }} / {{ stock(producto) }}</td>
                                         <td>
                                             <select class="form-select" v-model="producto.selectedColor">
                                                <option selected disabled>Color</option>
                                            <option v-for="(variante, index) in producto.product_variant" :key="index" :value="variante.color">{{ variante.color }}</option>
                                        </select>

                                         </td>
                                         <td>
                                            <select class="form-select" aria-label="Seleccionar talla" v-model="producto.selectedSize">
                                                <option selected disabled>Talla</option>
                                                <template v-for="(variante, index) in producto.product_variant" :key="index">
                                                    <!-- Verifica si el color de la variante coincide con el color seleccionado -->
                                                    <template v-if="variante.color === producto.selectedColor">
                                                        <!-- Itera sobre las tallas de cada variante y genera una opción por talla -->
                                                        <option v-for="(talla, i) in parseVariant(variante.variant)" :key="i">{{ talla }}</option>
                                                    </template>
                                                </template>
                                            </select>

                                         </td>
                                         <td>
                                             <button class="btn btn-sm btn-danger" @click="restarCantidad(producto)">-</button>
                                         </td>
                                         <td>
                                             <button v-if="stock(producto) > producto.cantidad" class="btn btn-sm btn-success" @click="sumarCantidad(producto)">+</button>
                                             <button v-else disabled class="btn btn-sm btn-success" @click="sumarCantidad(producto)">+</button>

                                         </td>
                                         <td>
                                             <button class="btn btn-sm btn-danger" @click="eliminarProd(producto.id)">Eliminar</button>
                                         </td>
                                     </tr>

                                     <tr v-else id="SinProductosId" >
                                         <td colspan="7">
                                             <div class="alert alert-danger text-center "> Sin Productos Añadidos</div>
                                         </td>
                                     </tr>

                                    <tr>
                                        <th scope="row">Total:</th>
                                        <td colspan="7" id="total-carrito">$ {{ total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                        </div>
                        <div class="row text-center">
                            <div class="col-6"><button @click="limpiar()" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i> Limpiar</button></div>
                            <div class="col-6">
                                <button @click="modalComprar()" class="btn btn-outline-success"><i class="fas fa-shopping-cart"></i> Comprar</button>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

        <!-- MODAL DETALLES -->
        <div class="modal fade" id="compraModal" tabindex="-1" role="dialog" aria-labelledby="compraModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="compraModalLabel">compra del Artículo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <form @submit.prevent="formSubmit">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" v-model="modelForm.name" placeholder="Ingrese su nombre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="apellido" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" id="apellido" v-model="modelForm.surname" placeholder="Ingrese su apellido" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" v-model="modelForm.address" placeholder="Ingrese su dirección" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ciudad" class="form-label">Ciudad</label>
                                        <input type="text" class="form-control" id="ciudad" v-model="modelForm.city" placeholder="Ingrese su ciudad" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" v-model="modelForm.email" placeholder="Ingrese su email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="codigoPostal" class="form-label">Código Postal</label>
                                        <input type="text" class="form-control" id="codigoPostal" v-model="modelForm.postalCode" placeholder="Ingrese su código postal" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="celular" class="form-label">Celular</label>
                                        <input type="text" class="form-control" id="celular" v-model="modelForm.phone" placeholder="Ingrese su celular" required>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                    </div>
                                </form>
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
import { ref, onMounted, computed } from 'vue';
import eventBus from '../utils/EventBus.js'; // Ajusta la ruta al archivo EventBus.js según tu estructura
import { Modal } from "bootstrap"

const storedData = ref(false)
const productsList = ref([])


const modelForm = ref({
    name: '',
    surname: '',
    address: '',
    city: '',
    email: '',
    postalCode: null,
    phone: null
})


function parseVariant(variantString) {
    const variantObject = JSON.parse(variantString);
    return variantObject.map(x => x.size);
}

const stock = (producto) => {

    const selectedVariant = producto.product_variant.flatMap(v => JSON.parse(v.variant)).find(v => v.size === producto.selectedSize);

    let Variant = producto.product_variant.map(v => v).find(v => v.color === producto.selectedColor);
  

    producto.selectedVariant = Variant

    localStorage.setItem('productos', JSON.stringify(productsList.value));        
    
    return producto.stockActual = selectedVariant ? selectedVariant.stock : 0;
            
}

function getProducts() {
    storedData.value = localStorage.getItem('productos')   
           
    if(storedData.value != '[]') {
                
        productsList.value = JSON.parse(storedData.value) 
    }else{
        storedData.value = false
    }
}

onMounted(() => {
    // Escuchar el evento 'updateCart' emitido desde 'Productos' para actualizar el contador
    eventBus.on('updateCart', getProducts);

    getProducts()    
})

function sumarCantidad(producto) {
    
    const indiceProducto = productsList.value.findIndex(p => p.id === producto.id);

    productsList.value[indiceProducto].cantidad += 1; // Aumentar la cantidad del producto existente en el carrito

    localStorage.setItem('productos', JSON.stringify(productsList.value));    

}
function restarCantidad(producto) {
    
    const indiceProducto = productsList.value.findIndex(p => p.id === producto.id);

     if ( productsList.value[indiceProducto].cantidad > 1) {
         productsList.value[indiceProducto].cantidad -= 1;
     }
     
     localStorage.setItem('productos', JSON.stringify(productsList.value));

    
}

function limpiar() {
    productsList.value = []
    storedData.value = false
    localStorage.removeItem('productos')
    
}

// Crea una propiedad computada para calcular el total
const total = computed(() => {
      let totalAmount = 0;

      for (const form of productsList.value) {
        if (form.cantidad) {
          totalAmount += form.amount * form.cantidad;
        }
      }

      return totalAmount.toLocaleString('es-CL');
});

function eliminarProd(id) {
    const index = productsList.value.findIndex(producto => producto.id === id);
    
    if(index !== -1) {
        productsList.value.splice(index, 1);
        localStorage.setItem('productos', JSON.stringify(productsList.value));

        getProducts()
    }

}


function modalComprar() {
    var compraModal = new Modal(document.getElementById('compraModal'));


        // Limpia las clases de animación al abrir el modal
        compraModal._element.classList.remove('animacion-fadeIn');


        // Animación al abrir el modal
        compraModal._element.classList.add('animacion-fadeIn');

        // Muestra el modal
        compraModal.show();

        eventBus.emit('updateModalCart'); 
}

async function formSubmit() {
    const response = await fetch('http://127.0.0.1:8000/api/v1/flow/create', {
        method: 'post',
        body: JSON.stringify({products: productsList.value,  userData: modelForm.value}),
        mode: 'cors',
        credentials: 'include'
    })

    const data = await response.json()
    
    window.location.href = data.url + '?token=' + data.token
    
}
</script>