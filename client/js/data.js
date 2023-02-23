// MODELO DE DATOS DEL COMPONENTE VUE
const modelComponent = {
    data:function(){},
    components:{},
    methods:{},
    template:'',
    created:function(){}
}

// MODELO DE DATOS DEL MENU/HEADER

const menu = [
    {id_menu: '1',recurso:'usuarios',icono:''},
    {id_menu: '2',recurso:'clientes',icono:''},
    {id_menu: '3',recurso:'libros',icono:''},
    {id_menu: '4',recurso:'recursos',icono:''}
]

// MODELOS DE DATOS DEL USUARIO
const modelUsuario={
    nombres:'',
    a_paterno:'',
    a_materno:'',
    telefono:'',
    f_nacimiento:'',
    id_perfil:0,
    usuario:'',
    email:'',
    contrasena:''
}

const modelUsuarios=[
    {id_usuario:'1',nombres:'John',a_paterno:'Doe',a_materno:'Duh',telefono:'+1 854 852 699',f_nacimiento:'1985-02-25',id_perfil:'1',perfil:'admin',usuario:'johnny23',email:'john.d@mail.com'},
    {id_usuario:'1',nombres:'John',a_paterno:'Doe',a_materno:'Duh',telefono:'+1 854 852 699',f_nacimiento:'1985-02-25',id_perfil:'1',perfil:'admin',usuario:'johnny23',email:'john.d@mail.com'},
    {id_usuario:'1',nombres:'John',a_paterno:'Doe',a_materno:'Duh',telefono:'+1 854 852 699',f_nacimiento:'1985-02-25',id_perfil:'1',perfil:'admin',usuario:'johnny23',email:'john.d@mail.com'}
]