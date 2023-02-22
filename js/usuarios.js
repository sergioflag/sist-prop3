const app = Vue.createApp({
    data(){
        return {
            usuario:{
                email:'',
                contrasena:''
            },
            showOverlay:false
        }
    },
    methods:{
        login(){

            if(this.usuario.email === '' || this.usuario.email === undefined || this.usuario.contrasena === '' || this.usuario.contrasena === undefined){
                swal({
                    icon:'warning',
                    text:'Los campos están vacíos o incompletos'
                })
            }else{

                const request = {
                    email:this.usuario.email,
                    contrasena:this.usuario.contrasena,
                }

                fetch('http://localhost/brian/sist-prop3/controllers/login/_api.php?opcion=login',{
                    method:'POST',
                    body:JSON.stringify(request),
                    headers:{
                        'content-type':'application/json'
                    }
                })
                .then(response=>response.json())
                .then((response)=>{
                    if(response.error === false){
                        localStorage.setItem('token',response.token)
                        
                        this.$emit('getOverlay',false);
                        setTimeout(() => {
                        }, 1500);
                        window.location.replace('http://localhost/brian/sist-prop3/views/usuarios.php')

                    }else{
                        swal({
                            icon:'error',
                            text:response.message
                        })
                    }
                })
            }

        },
        returnOverlay(){
            this.showOverlay = true;
        }
    }
})

app.mount('#app')