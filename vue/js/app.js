const app = Vue.createApp({
    components:{
        'login-component':loginComponent,
        'usuarios-component':usuariosComponent,
        'lectores-component':lectoresComponent,
        'libros-component':librosComponent,
        'prestamos-component':prestamosComponent,
    }
})
.mount('#app')