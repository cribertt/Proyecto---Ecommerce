<template>
    <main>
        <h1>Compra Exitosa</h1>
        <p>¡Gracias por tu compra! Tu pedido ha sido procesado exitosamente.</p>
        <img src="../../compra_exitosa.webp" alt="Compra Exitosa">
    </main>
    <footer>
        <p>&copy; 2024 Tu Empresa. Todos los derechos reservados.</p>
    </footer>
</template>
<script setup lang="ts">
import { onMounted } from 'vue';
onMounted(() => {
  localStorage.removeItem('productos'); 
  setTimeout(() => {
    window.location.href = '/'
  }, 3000)
})

</script>