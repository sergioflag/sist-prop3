const loginComponent = {
    data(){
        return {
            usuario:{
                email:'',
                contrasena:''
            }
        }
    },
    methods:{
        login(){

            if(this.usuario.email === '' || this.usuario.email === undefined || this.usuario.contrasena === '' || this.usuario.contrasena === undefined){
                alert('Los campos están vacíos')
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
                    console.log(response)
                })
            }

        }
    },
    template:`
    <div>
        <form class="rounded-md p-2 flex flex-col bg-indigo-500 gap-4 px-4 py-4">

            <div class="flex flex-col gap-4">
                <span class="text-xl text-indigo-100 font-bold">¡Bienvenido!</span>
                <span class="text-md text-indigo-100 font-regular">Para continuar ingrese sus datos...</span>
            </div>

            <div class="flex flex-col gap-4">
                <label for="email" class="text-indigo-200 text-md font-bold">Correo electrónico: </label>
                <input v-model="usuario.email" type="email" class="text-indigo-800 p-2 rounded-md" placeholder="Ex. name@mail.com">
            </div>

            <div class="flex flex-col gap-4">
                <label for="contrasena" class="text-indigo-200 text-md font-bold">contraseña: </label>
                <input v-model="usuario.contrasena" type="password" class="text-indigo-800 p-2 rounded-md" placeholder="XXXXXXXXXX">
            </div>

            <div class="flex flex-col gap-4">
                <button type="button" @click="login()" class="rounded-md text-indigo-200 bg-indigo-800 p-2 hover:bg-indigo-200 hover:text-indigo-800 text-xl font-bold">Iniciar Sesión</button>
            </div>

        </form>
    </div>

    `
}