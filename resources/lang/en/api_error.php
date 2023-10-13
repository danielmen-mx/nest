<?php

return [
    'auth' => [
        'register' => "Something goes wrong. Try again",
        'login' => [
            'user' => "The user doesnt exist",
            'email' => "The email doesnt exist",
            'password' => "The password is incorrect",
            'deactivated' => "The user is currently disabled, contact your administrator",
        ],
        'validation' => [
            'register' => [
                'email' => "The email you used already exists"
            ],
            'login' => [
                'email' => "Email or Username is required",
                'password' => "Password is required"
            ]
        ]
    ],
    'posts' => [
        'index' => "Something went wrong. Try later",
        'store' => "Sorry, we couldn't create that post. Try later",
        'show'  => "Sorry, we couldn't get that post. Try later",
        'update' => "Sorry, we couldn't update that post. Try later",
        'delete' => "Sorry, we couldn't delete that post. Try later",
        'validation' => [
            'name' => "Title is required and must be unique",
            'autor' => "Autor is required",
            'description' => "Description is required",
            'image' => "Image property must be file image type"
        ]
    ],
    'products' => [
        'index' => "Something went wrong. Try later",
        'store' => "Sorry, we couldn't create that product. Try later",
        'show'  => "Sorry, we couldn't get that product. Try later",
        'update' => "Sorry, we couldn't update that product. Try later",
        'delete' => "Sorry, we couldn't delete that product. Try later",
        'validation' => [
            //
        ]
    ],
    'comments' => [
        'index' => "Something went wrong. Try later",
        'store' => "Sorry, we couldn't add that comment. Try later",
        'show'  => "Sorry, we couldn't get that comment. Try later",
        'update' => "Sorry, we couldn't update that comment. Try later",
        'delete' => "Sorry, we couldn't delete that comment. Try later",
        'validation' => [
            'user_id'    => "The user ID is required",
            'comment'    => "The comment must have at least 1 character",
            'model_type' => "The model type is required",
            'model_id' => "The model ID is required",
        ]
    ],
    'reactions' => [
        'index' => "Something went wrong. Try later",
        'store' => "Sorry, we couldn't create the reaction. Try later",
        'show'  => "Sorry, we couldn't get that reaction. Try later",
        'update' => "Sorry, we couldn't update that reaction. Try later",
        'delete' => "Sorry, we couldn't delete that reaction. Try later",
        'validation' => [
            'user_id' => "The user_id is required and must be valid",
            'model_type' => "The model_type is required",
            'model_id' => "The model_id is required",
            'reaction' => "The reaction is required and must be boolean type"
        ]
    ],
    'users' => [
        'index' => "Something went wrong. Try later",
        'store' => "Sorry, we couldn't create that user. Try later",
        'show'  => "Sorry, we couldn't get that user. Try later",
        'update' => "Sorry we couldn't update that user. Try later",
        'delete' => "Sorry, we couldn't delete that user. Try later",
        'validation' => [
            'username' => 'The username must have max 100 characters',
            'email' => 'The email is required and must be unique',
            'is_admin' => 'The is_admin property must be boolean type',
            'first_name' => 'The first_name is required and must be max 100 characters',
            'last_name' => 'The last_name is required and must be max 100 characters',
            'language' => "The current languages availables are: 'en' or 'es'",
            'duplicated_password' => "The password is already in use",
            'duplicated_username' => "The username cannot be the same as the one you have",
            'username_in_use' => "The username is already in use by someone else",
            'duplicated_email' => "The email cannot be the same as the one you have",
            'email_in_use' => "The email is already in use by someone else",
            'email_validation' => "The email is required and must be valid",
            "username_empty_strings" => "Username cannot contain whitespace",
            "email_empty_strings" => "Email cannot contain whitespace"
        ],
        'username' => "Username isn't available. Please try another",
        'email' => "Email isn't available. Please try another"
    ]
];