<?php

return [
    'auth' => [
        'login' => [
            'user' => "The user doesnt exist",
            'email' => "The email doesnt exist",
            'password' => "The password is incorrect",
            'deactivated' => "The user is currently disabled, contact your administrator",
            'validation' => [
                'email' => "Email or Username is required",
                'password' => "Password is required"
            ]
        ],
        'register' => [
            'email' => "The email you used already exists"
        ],
    ],
    'posts' => [
      'index' => "Something went wrong. Try again",
      'store' => "Sorry, we couldn't create that post. I try again",
      'update' => "Sorry we couldn't update that post. Try again",
      'delete' => "Sorry, we couldn't delete that post. he tries again",
      'validation' => [
        'name' => "Title is required and must be unique",
        'autor' => "Autor is required",
        'description' => "Description is required",
        'image' => "Image property must be file image type"
      ]
    ]
];