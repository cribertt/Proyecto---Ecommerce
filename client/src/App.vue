<script setup>
import { RouterLink, RouterView } from 'vue-router'
import HeaderComponent  from './components/Header.vue'
import NavBar from './components/NavBar.vue'
</script>

<template>
  
  <HeaderComponent/>
  <NavBar/>
  
  <RouterView />
</template>
