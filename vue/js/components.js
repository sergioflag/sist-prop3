const loaderComponent = {
    template:'#loader-template'
}

const menuComponent = {
    template:'#menu-template',
    data:function(){
        return {
            menu:[
                //{id_menu:'1',recurso:'usuarios'},
                //{id_menu:'2',recurso:'lectores'},
                //{id_menu:'2',recurso:'libros'},
                //{id_menu:'3',recurso:'prestamos'}
            ],
            usuario:{
                nombre:'',
                perfil:''
            }
        }
    },
    methods:{
        pathValidate:function(menu){
            let path = window.location.pathname.split('/');
            path=path[path.length-1];
            
            let pages = menu.map((element)=>{
                return element.recurso+'.html'
            })
    
            if(pages.includes(path)===false){
                console.log(`El acceso al recurso ${path} está restringido`)
                window.location.replace('http://localhost/brian/sist-prop3/vue/views/lectores.html')
            }
        },
        getMenu:function(){
            if(localStorage['token']!== undefined){

                fetch('http://localhost/brian/sist-prop3/controllers/login/_api.php',{
                    method:'GET',
                    headers:{
                        'content-type':'application/json',
                        'token':localStorage['token']
                    }
                })
                .then(res=>res.json())
                .then((response)=>{
                    console.log(response)
                    if(response.error === false){
                        this.menu = response.data.menu
                        this.usuario = response.data.usuario

                        this.pathValidate(this.menu)
                    }else{
                        swal({
                            icon:'warning',
                            text:response.message
                        })
                    }
                })
            }else{
                window.location.replace('http://localhost/brian/sist-prop3/vue/views/login.html')
            }
        },
        salir:function(){
            localStorage.removeItem('token')
            window.location.replace('http://localhost/brian/sist-prop3/vue/views/login.html')
        }
    },
    created:function(){
        this.getMenu()
        //this.pathValidate(this.menu)
    }
}

const usuariosComponent = {
    template:'#usuarios-template',
    components:{
        'menu-component':menuComponent
    }
}

const lectoresComponent = {
    template:'#lectores-template',
    components:{
        'menu-component':menuComponent
    }
}

const librosComponent = {
    template:'#libros-template',
    components:{
        'menu-component':menuComponent
    }
}

const prestamosComponent = {
    template:'#prestamos-template',
    components:{
        'menu-component':menuComponent
    }
}

const loginComponent = {
    template:'#login-template',
    components:{
        'loader-component':loaderComponent
    },
    data:function(){
        return{
            usuario:{
                email:'',
                contrasena:''
            },
            showLoader:false
        }
    },
    methods:{
        login:function(){
            if(this.usuario.email === undefined || this.usuario.email === '' || this.usuario.contrasena === undefined || this.usuario.contrasena === ''){
                swal({
                    icon:'warning',
                    text:'Los campos están incompletos'
                })
            }else{

                fetch('http://localhost/brian/sist-prop3/controllers/login/_api.php',{
                    method:'POST',
                    body:JSON.stringify({email:this.usuario.email,contrasena:this.usuario.contrasena}),
                    headers:{
                        'content-type':'application/json'
                    }
                })
                .then(res=>res.json())
                .then((response)=>{
                    //console.log(response)

                    if(response.error === false){
                        localStorage.setItem('token',response.token);
                        this.showLoader = true;
                        setTimeout(() => {
                            window.location.replace('http://localhost/brian/sist-prop3/vue/views/lectores.html')
                            this.showLoader = false
                        }, 1500);
                    }else{
                        swal({
                            icon:'warning',
                            text:response.message
                        })
                    }
                })
            }
        },
        getSession:function(){
            if(localStorage['token'] !== undefined){
                this.showLoader = true;
                setTimeout(() => {
                    window.location.replace('http://localhost/brian/sist-prop3/vue/views/lectores.html');
                    this.showLoader = true;
                }, 1500);
            }
        }
    },
    created:function(){

        this.showLoader = true;
        setTimeout(() => {
            this.showLoader = false;
        }, 1500);

        this.getSession()
    }
}