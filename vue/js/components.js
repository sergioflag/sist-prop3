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
            },
            titulo:'MyLibrary'
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
                        localStorage.removeItem('token')
                        window.location.replace('http://localhost/brian/sist-prop3/vue/views/login.html')
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
        //console.log(this.listaUsuarios)
    }
}

const usuariosComponent = {
    template:'#usuarios-template',
    components:{
        'menu-component':menuComponent,
    },
    data:function(){
        return {
            listaUsuarios:[],
            showList:false,
            updateForm:false,
            updatePassword:false,
            action:'',
            userForm:{
                nombres:'',
                a_paterno:'',
                a_materno:'',
                telefono:'',
                f_nacimiento:'',
                id_perfil:'',
                usuario:'',
                email:'',
                contrasena:''
            },
            passwordForm:{
                email:'',
                contrasena:'',
                nueva_contrasena:''
            },
            profiles:[
                {id_perfil:1,perfil:'administrador'},
                {id_perfil:2,perfil:'recepcionista'},
            ]
        }
    },
    methods:{
        getUsuarios:function(){
            fetch('http://localhost/brian/sist-prop3/controllers/usuarios/_api.php',{
                method:'GET',
                headers:{
                    'content-type':'application/json'
                }
            })
            .then(res=>res.json())
            .then((response)=>{
                console.log(response)
                this.listaUsuarios = response.usuarios
            })
        },
        btnNuevo:function(){
            this.showList = false,
            this.updateForm = false,
            this.updatePassword = false
            this.action = 'Registrar nuevo usuario'
        },
        btnCancelar:function(){
            this.showList = true,
            this.updateForm = false,
            this.updatePassword = false
            this.action = 'Lista de usuarios'
            this.clearForm()
        },
        actualizarContrasena:function(){
            this.showList = false,
            this.updateForm = false,
            this.updatePassword = true
            this.action = 'Actualizar contraseña'
        },
        btnEliminar:function(item){
            item = JSON.parse(JSON.stringify(item))
            const index = this.listaUsuarios.findIndex((element)=>element.id_usuario === item.id_usuario)
            let id = item.id_usuario

            swal({
                icon:'warning',
                title:'¿Estás seguro de eliminar el registro?',
                text:'El cambio es irreversible',
                buttons:true,
                dangerMode:true
            })
            
            .then((eliminar)=>{

                if(eliminar){

                    let url = `http://localhost/brian/sist-prop3/controllers/usuarios/_api.php?id=${id}`

                    
                    fetch(url,{
                        method:'DELETE',
                        headers:{
                            'content-type':'application/json'
                        }
                    })
                    .then(res=>res.json())
                    .then((response)=>{

                        //console.log(response.error)

                        let error = response.error
                        let message = response.message
                        
                        if(error === false){

                            this.listaUsuarios[index]=item
                            this.listaUsuarios.splice(index,1)

                            swal(message,{
                                icon:'success'
                            })
                            this.btnCancelar()
                        }else{
                            swal(message,{
                                icon:'error'
                            })
                            this.btnCancelar()

                        }

                    })

                    

                }else{
                    this.btnCancelar()
                }
                
            })
            //console.log(item)
        },
        selectCard:function(item){
            this.showList = false,
            this.updateForm = true,
            this.updatePassword = false
            this.action = 'Actualizar usuario'
            item = JSON.parse(JSON.stringify(item))
            this.userForm = item
    
            console.log(item)
        },
        guardarUsuario:function(){
            let item = JSON.parse(JSON.stringify(this.userForm))
            console.log(item)
                        
            const formData = Object.values(item)

            if(item.id_perfil === 1 || item.id_perfil === '1'){
                item.perfil = 'administrador'
            }else{
                item.perfil = 'recepcionista'
            }
            
            if(formData.includes('')){
                swal({
                    icon:'warning',
                    text:'Los campos están incompletos'
                })
            }else{

                fetch('http://localhost/brian/sist-prop3/controllers/usuarios/_api.php',{
                    method:'POST',
                    body:JSON.stringify(item),
                    headers:{
                        'content-type':'json/application'
                    }
                })
                .then(res=>res.json())
                .then((response)=>{
                    console.log(response)
                    this.listaUsuarios.push(item)
                    if(response.error === false){
                        swal(response.message,{
                            icon:'success'
                        })
                        this.clearForm()
                        this.btnCancelar()

                    }else{
                        swal(response.message,{
                            icon:'error'
                        })
                        this.clearForm()
                        this.btnCancelar()
                    }
                })
            }
        },
        actualizarUsuario:function(){
            
            let item = JSON.parse(JSON.stringify(this.userForm))
            const index = this.listaUsuarios.findIndex((element)=>element.id_usuario === item.id_usuario)
            let id = item.id_usuario

            const formData = Object.values(item)

            if(item.id_perfil === 1 || item.id_perfil === '1'){
                item.perfil = 'administrador'
            }else{
                item.perfil = 'recepcionista'
            }

            if(formData.includes('')){
                swal({
                    icon:'warning',
                    text:'Los campos están incompletos'
                })
            }else{

                swal({
                    icon:'warning',
                    title:'¿Deseas efectuar los cambios?',
                    text:'Los cambiós aplicados serán registrados una vez aceptada la opción',
                    buttons:true,
                    dangerMode:true
                })
                .then((update)=>{
                    if(update){

                        //console.log(item)
                        
                        fetch(`http://localhost/brian/sist-prop3/controllers/usuarios/_api.php?id=${id}`,{
                            method:'PUT',
                            body:JSON.stringify(item),
                            headers:{
                                'content-type':'application/json'
                            }
                        })
                        .then(res=>res.json())
                        .then((response)=>{

                            //console.log(response)

                            
                            if(response.error === false){
                                swal(response.message,{
                                    icon:'success'
                                })
                                this.clearForm()
                                this.btnCancelar
                            }else{
                                swal(response.message,{
                                    icon:'warning'
                                })
                                this.btnCancelar()
                                this.clearForm()
                            }
                            
                        })
                        

                    }else{
                        this.btnCancelar()
                        this.clearForm()
                    }
                    this.btnCancelar()
                    this.clearForm()
                })

            }

        },
        btnActualizarContrasena:function(){
            let item = JSON.parse(JSON.stringify(this.passwordForm))
            const formData = Object.values(item)

            if(formData.includes('')){
                swal({
                    icon:'warning',
                    text:'Los campos están incompletos'
                })
            }else{

                swal({
                    icon:'warning',
                    title:'¿Deseas efectuar los cambios?',
                    text:'Los cambios quedarán registrados de forma permanente',
                    buttons:true,
                    dangerMode:true
                })
                
                .then((actualizar)=>{
                    if(actualizar){
                        
                        //console.log(item)
                        
                        fetch('http://localhost/brian/sist-prop3/controllers/login/_api.php',{
                            method:'PUT',
                            body:JSON.stringify(item),
                            headers:{
                                'content-type':'application/json'
                            }
                        })
                        .then(res=>res.json())
                        .then((response)=>{
                            console.log(response)

                            if(response.error === false){
                                swal(response.message,{
                                    icon:'success'
                                })
                                this.clearPasswordForm()
                                this.btnCancelar()
                            }else{
                                swal(response.message,{
                                    icon:'error'
                                })
                                this.clearPasswordForm()
                                this.btnCancelar()
                            }
                        })
                        this.clearPasswordForm()
                        this.btnCancelar()
                    }else{
                        this.clearPasswordForm()
                        this.btnCancelar()
                    }
                })
            }

            this.clearPasswordForm()
        },
        clearForm:function(){

            this.userForm = {
                nombres:'',
                a_paterno:'',
                a_materno:'',
                telefono:'',
                f_nacimiento:'',
                id_perfil:'',
                usuario:'',
                email:'',
                contrasena:''
             }
        },
        clearPasswordForm:function(){
            this.passwordForm = {
                email:'',
                contrasena:'',
                nueva_contrasena:''
            }
        }

    },
    created(){
        this.showList = true,
        this.updateForm = false,
        this.updatePassword = false
        this.action = 'Lista de usuarios'
        this.getUsuarios()
        //console.log(this.listaUsuarios.length)
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