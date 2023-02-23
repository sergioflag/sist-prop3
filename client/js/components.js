const loaderComponent = {
    template:'#loader-template',
}


const loginComponent = {
    components:{
        'loader-component':loaderComponent
    },
    data(){
        return{
            title:'login',
            showLoader:false,
            usuario:{
                email:'',
                contrasena:'',
            }
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
                        this.showLoader = true;
                        setTimeout(() => {
                            window.location.replace('http://localhost/brian/sist-prop3/client/views/lectores.html')
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

            if(localStorage['token'] !== undefined){

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
                        this.showLoader = true;
                        setTimeout(() => {
                            window.location.replace('http://localhost/brian/sist-prop3/client/views/lectores.html')
                        }, 2000);
                        
                        
                    }else{
                        swal({
                            icon:'error',
                            text:response.message
                        })
                        this.showLoader = true;

                        setTimeout(() => {
                            localStorage.removeItem('token')
                            this.showLoader = false;
                            window.location.replace('http://localhost/brian/sist-prop3/client/views/login.html')
                        }, 2000);
                    }
                })

            }else{
                localStorage.removeItem('token')
            }
        }
    },
    created(){
        this.getSession()
    }
}

const usuarioComponent = {
    data:function(){
        return{
            usuario:{}
        }
    },
    methods:{},
    template:'#usuario-template',
    created:function(){}
}

const menuComponent = {
    data:function(){
        return{
            titulo:'MyApp',
            menu:[],
            usuario:{}
        }
    },
    methods:{
        getMenu:function(){

            if(localStorage['token'] !== undefined){

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

                        this.usuario = response.data.usuario
                        this.menu = response.data.menu

                    }else{
                        localStorage.removeItem('token')
                        window.location.replace('http://localhost/brian/sist-prop3/client/views/login.html')
                    }
                })

            }else{
                localStorage.removeItem('token')
                window.location.replace('http://localhost/brian/sist-prop3/client/views/login.html')
            }
        },
        logout:function(){
            localStorage.clear()
            window.location.replace('http://localhost/brian/sist-prop3/client/views/login.html')
        },
        validatePath:function(){

            let page = window.location.pathname.split('/')
            page = page[page.length-1]

            var pages = this.menu.map((element)=>{
                return element.recurso+'.html'
            })

            if(pages.includes(page)){
                console.log('Acceso correcto')
            }else{
                swal({
                    icon:'warning',
                    text:'No tienes acceso a este contenido'
                })
                window.location.replace('http://localhost/brian/sist-prop3/client/views/lectores.html')
            }

        }
    },
    template:'#menu-template',
    created:function(){
        this.getMenu()
    }
}

const lectorComponent = {
    data:function(){
        return{
            usuario:{}
        }
    },
    methods:{},
    template:'#lector-template',
    created:function(){}
}

const lectoresComponent = {
    data:function(){
        return{
            usuario:{}
        }
    },
    methods:{},
    components:{
        'menu-component':menuComponent,
        'lector-component':lectorComponent
    },
    template:'#lectores-template',
    created:function(){}
}

const pruebaComponent = {

}

const usuariosComponent = {
    data:function(){
        return{
            showLoader:false,
            showUsuarioForm:false,
            showListaUsuarios:false,
            listaUsuarios:[
                {id_usuario:'1',nombres:'John',a_paterno:'Doe',a_materno:'Duh',telefono:'+1 854 852 699',f_nacimiento:'1985-02-25',id_perfil:'1',perfil:'admin',usuario:'johnny23',email:'john.d@mail.com'},
                {id_usuario:'1',nombres:'John',a_paterno:'Doe',a_materno:'Duh',telefono:'+1 854 852 699',f_nacimiento:'1985-02-25',id_perfil:'1',perfil:'admin',usuario:'johnny23',email:'john.d@mail.com'},
                {id_usuario:'1',nombres:'John',a_paterno:'Doe',a_materno:'Duh',telefono:'+1 854 852 699',f_nacimiento:'1985-02-25',id_perfil:'1',perfil:'admin',usuario:'johnny23',email:'john.d@mail.com'}
            ]
        }
    },
    components:{
        'menu-component':menuComponent,
        'usuario-component':usuarioComponent,
    },
    template:'#usuarios-template',
}


