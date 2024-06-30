import { createRouter, createWebHistory } from 'vue-router'

import Index from '../views/index.vue'
import Productos from '../views/productos.vue'
import Nosotros from '../views/nosotros.vue'
import PaymentReturn from '../views/payment/return.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Index
    },
    {
      path: '/productos',
      name: 'productos',
      component: Productos
    },
    {
      path: '/nosotros',
      name: 'nosotros',
      component: Nosotros
    }, 
    {
      path: '/payment/return',
      name: 'payment',
      component: PaymentReturn
    },    
  ]
})

export default router
