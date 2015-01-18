<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home'  => [
           'icon' => 'home',
            'text'  => 'Hem',   
            'url'   => '',  
            'title' => 'Me-Site'
        ],
 
        // This is a menu item
        'redovisning'  => [
        'icon' => 'puzzle-piece',
            'text'  => 'Redovisning',   
            'url'   => 'redovisning',   
            'title' => 'Redovisning skriver vi här',

        ],
 
                // This is a menu item
        'comments'  => [
        'icon' => 'comments',
            'text'  => 'Kommentarer',   
            'url'   => 'kommentarer',   
            'title' => 'Kommentarer',

        ],
        
         // This is a menu item

        'tema'  => [
        'icon' => 'th',
            'text'  => 'Tema',   
            'url'   => 'theme',   
            'title' => 'Tema',
            
        'submenu' => [
        
                'items' => [
                     'item 1'  => [
                     'icon' => 'list',
            'text'  => 'Typography',   
            'url'   => 'theme/typography',   
            'title' => 'Typography',

        ], 
       

                'item 2'  => [
                'icon' => 'font',
            'text'  => 'Font Awesome',
            'url'   => 'theme/font',
            'title' => 'Awesome font här',
    
           
],

],
],
],
     
// This is another menu item
        'users' => [
        'icon' => 'user',
            'text'  =>'Användare', 
            'url'   =>'users/list/active',  
            'title' => 'Användare',
        
            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'item 3'  => [
                    'icon' => 'plus-square',
                        'text'  => 'Skapa användare',   
                        'url'   => 'users/add',
                        'title' => 'Lägg till användare.'
                    ],                     

                    // This is a menu item of the submenu
                    'item 4'  => [
                    'icon' => 'plus-square-o',
                        'text'  => 'Setup/Reset DB',   
                        'url'   => 'users/setup-users',  
                        'title' => 'Skapa Tabell och ett par Användare'
                    ], 
                ],
            ], 
        ],

         // This is a menu item
        'status-test' => [
        'icon' => 'exclamation-triangle',
            'text'  =>'Statusmeddelanden', 
            'url'   =>'status-test',  
            'title' =>'Testsida för statusmeddelanden'
        ],

        
        // This is a menu item
        'source' => [
        'icon' => 'code',
            'text'  =>'Source', 
            'url'   =>'source',  
            'title' =>'Källkod'
        ],
    ],
 
    // Callback tracing the current selected menu item base on scriptname
    'callback' => function($url) {
        if ($url == $this->di->get('request')->getRoute()) {
            return true;
        }
    },

    // Callback to create the urls
    'create_url' => function($url) {
        return $this->di->get('url')->create($url);
    },
];
