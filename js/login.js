const app = Vue.createApp({
    created(){
        this.showOverlay = true
        
        setTimeout(() => {
            this.showOverlay = false
        }, 1500);

        this.getSession()
    },
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

                fetch('http://localhost/brian/sist-prop3/controllers/login/_api.php',{
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
                        this.showOverlay = true;
                        setTimeout(() => {
                            window.location.replace('http://localhost/brian/sist-prop3/views/usuarios.php')
                        }, 2000);


                    }else{
                        swal({
                            icon:'error',
                            text:response.message
                        })
                    }
                })
            }

        },

        getSession(){

            if(localStorage.getItem('token') !== undefined){

                fetch('http://localhost/brian/sist-prop3/controllers/login/_api.php',{
                    method:'GET',
                    headers:{
                        'content-type':'application/json',
                        'token':localStorage['token']
                    }
                })
                .then(response=>response.json())
                .then((response)=>{
                    if(response.error === false){
                        this.showOverlay = true;
                        setTimeout(() => {
                            window.location.replace('http://localhost/brian/sist-prop3/views/usuarios.php')
                        }, 2000);
                        
                        
                    }else{
                        swal({
                            icon:'error',
                            text:response.message
                        })
                        localStorage.removeItem('token')
                        window.location.replace('http://localhost/brian/sist-prop3/views/login.php')
                    }
                })
            }else{
                localStorage.removeItem('token')
                window.location.replace('http://localhost/brian/sist-prop3/views/login.php')
            }
        }
    },
    components:{
        overlay
    }
})

app.mount('#app')