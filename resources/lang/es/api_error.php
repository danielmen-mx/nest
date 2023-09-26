<?php

return [
    'auth' => [
        'register' => "Algo salio mal. Intenta de nuevo",
        'login' => [
            'user' => "El usuario no existe",
            'email' => "El correo no existe",
            'password' => "Credenciales no validas",
            'deactivated' => "El usuario actualmente esta desactivado, contacte a su administrador",
        ],
        'validation' => [
            'register' => [
                'email' => "El email que usaste ya está en uso. Inicia sesión"
            ],
            'login' => [
                'email' => "Email o Username es requerido",
                'password' => "Contraseña es requerida"
            ]
        ],
    ],
    'posts' => [
        'index' => "Algo salió mal. Intenta más tarde",
        'store' => "Lo siento, no pudimos crear esa publicación. Intenta más tarde",
        'show'  => "Lo siento, no pudimos obtener esa publicación. Intenta más tarde",
        'update' => "Lo siento, no pudimos actualizar esa publicación. Intenta más tarde",
        'delete' => "Lo siento, no pudimos eliminar esa publicación. Intenta más tarde",
        'validation' => [
            'name' => "Titulo es requerido y debe ser unico",
            'autor' => "Autor es requerido",
            'description' => "Descripcion es requerido",
            'image' => "Propiedad imagen debe ser tipo imagen"
        ]
    ],
    'users' => [
        'index' => "Algo salió mal. Intenta más tarde",
        'store' => "Lo siento, no pudimos crear ese usuario. Intenta más tarde",
        'show' => "Lo siento, no pudimos obtener ese usuario. Intenta más tarde",
        'update' => "Lo siento, no pudimos actualizar ese usuario. Intenta más tarde",
        'delete' => "Lo siento, no pudimos eliminar ese usuario. Intenta más tarde",
        'validation' => [
            'username' => 'El usuario debe tener 100 caracteres máximo',
            'email' => 'El email ya se encuentra registrado',
            'is_admin' => 'La propiedad is_admin debe ser del tipo booleano',
            'first_name' => 'El primer nombre es requerido y debe contener máximo 100 caracteres',
            'last_name' => 'El apellido es requerido y debe contener máximo 100 caracteres',
            'language' => "Los lenguajes disponibles son: 'en' ó 'es'",
            'duplicated_password' => "La contraseña debe ser diferente a la actual",
            'duplicated_username' => "El usuario no puede ser el mismo que tienes",
            'username_in_use' => "El usuario ya esta en uso por alguien más",
            'duplicated_email' => "El email no puede ser el mismo que tienes",
            'email_in_use' => "El email ya esta en uso por alguien más",
            'email_validation' => "El email es requerido y debe ser uno valido"
        ],
        'username' => "El nombre de usuario no está disponible. Intenta con otro",
        'email' => "El email no está disponible. Intenta con otro"
    ]
];