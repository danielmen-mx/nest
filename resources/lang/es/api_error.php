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
        'index' => "Algo salió mal. Intenta nuevamente",
        'store' => "Lo siento, no pudimos crear esa publicación. Intenta nuevamente",
        'update' => "Lo siento, no pudimos actualizar esa publicación. Intenta nuevamente",
        'delete' => "Lo siento, no pudimos eliminar esa publicación. Intenta nuevamente",
        'validation' => [
            'name' => "Titulo es requerido y debe ser unico",
            'autor' => "Autor es requerido",
            'description' => "Descripcion es requerido",
            'image' => "Propiedad imagen debe ser tipo imagen"
        ]
    ]
];