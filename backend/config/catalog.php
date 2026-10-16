<?php
// Images live in public/images/catalog/<category>/<file>. Prices are EXAMPLES - replace them.
$base = 'https://www.mcdonalds.ma/notre-menu/nos-produits/';

return [
    'meal_delivery' => 15,
    'categories' => [
        [
            'id' => 'mcdo', 'name' => 'Macdonald', 'logo' => 'mcdo/logo.png',
            'menu_page' => 'https://www.mcdonalds.ma/notre-menu',
            'options' => [
                ['name' => 'Menus', 'url' => $base.'menus', 'items' => [
                    ['name' => 'Menu Big Mac', 'price' => 62, 'meal' => true, 'image' => 'mcdo/menu-big-mac.jpg'],
                    ['name' => 'Menu McChicken', 'price' => 52, 'meal' => true, 'image' => 'mcdo/menu-mcchicken.jpg'],
                    ['name' => 'Menu Chicken McNuggets', 'price' => 55, 'meal' => true, 'image' => 'mcdo/menu-nuggets.jpg'],
                ]],
                ['name' => 'Burgers', 'url' => $base.'burgers', 'items' => [
                    ['name' => 'Big Mac', 'price' => 42, 'image' => 'mcdo/big-mac.jpg'],
                    ['name' => 'Cheeseburger', 'price' => 18, 'image' => 'mcdo/cheeseburger.jpg'],
                    ['name' => 'McChicken', 'price' => 32, 'image' => 'mcdo/mcchicken.jpg'],
                ]],
                ['name' => 'Wraps', 'url' => $base.'wraps', 'items' => [
                    ['name' => 'McWrap Chicken', 'price' => 34, 'image' => 'mcdo/mcwrap-chicken.jpg'],
                    ['name' => 'McWrap Crispy', 'price' => 36, 'image' => 'mcdo/mcwrap-crispy.jpg'],
                ]],
                ['name' => 'Happy Meal®', 'url' => $base.'happy_meal', 'items' => [
                    ['name' => 'Happy Meal Cheeseburger', 'price' => 38, 'meal' => true, 'image' => 'mcdo/happy-cheeseburger.jpg'],
                    ['name' => 'Happy Meal Nuggets', 'price' => 40, 'meal' => true, 'image' => 'mcdo/happy-nuggets.jpg'],
                ]],
                ['name' => 'Duo Mabrouk', 'url' => $base.'menus-duo-mabrouk', 'items' => [
                    ['name' => 'Duo Mabrouk', 'price' => 70, 'meal' => true, 'image' => 'mcdo/duo-mabrouk.jpg'],
                ]],
                ['name' => 'Menu Mabrouk', 'url' => $base.'menu_mabrouk', 'items' => [
                    ['name' => 'Menu Mabrouk', 'price' => 45, 'meal' => true, 'image' => 'mcdo/menu-mabrouk.jpg'],
                ]],
                ['name' => 'À partager', 'url' => $base.'a-partager', 'items' => [
                    ['name' => 'Box à partager', 'price' => 89, 'meal' => true, 'image' => 'mcdo/box.jpg'],
                ]],
                ['name' => 'Frites', 'url' => $base.'frites', 'items' => [
                    ['name' => 'Petite frite', 'price' => 10, 'image' => 'mcdo/frites-s.jpg'],
                    ['name' => 'Grande frite', 'price' => 18, 'image' => 'mcdo/frites-l.jpg'],
                ]],
                ['name' => 'Glaces & Desserts', 'url' => $base.'glaces_desserts', 'items' => [
                    ['name' => 'McFlurry™', 'price' => 22, 'image' => 'mcdo/mcflurry.jpg'],
                    ['name' => 'Sundae', 'price' => 15, 'image' => 'mcdo/sundae.jpg'],
                ]],
                ['name' => 'Boissons', 'url' => $base.'boissons', 'items' => [
                    ['name' => 'Coca-Cola', 'price' => 12, 'image' => 'mcdo/coca.jpg'],
                    ['name' => 'Eau', 'price' => 6, 'image' => 'mcdo/eau.jpg'],
                ]],
                ['name' => 'Salades et sauces', 'url' => $base.'salades-sauces', 'items' => [
                    ['name' => 'Salade', 'price' => 28, 'image' => 'mcdo/salade.jpg'],
                    ['name' => 'Sauce', 'price' => 4, 'image' => 'mcdo/sauce.jpg'],
                ]],
                ['name' => 'Petit déjeuner', 'url' => $base.'petit_dejeuner', 'items' => [
                    ['name' => 'Menu petit déjeuner', 'price' => 30, 'meal' => true, 'image' => 'mcdo/petit-dej.jpg'],
                ]],
                ['name' => 'McCafé®', 'url' => $base.'mccafe', 'items' => [
                    ['name' => 'Cappuccino', 'price' => 18, 'image' => 'mcdo/cappuccino.jpg'],
                    ['name' => 'Café latte', 'price' => 20, 'image' => 'mcdo/latte.jpg'],
                ]],
            ],
        ],
        
            ...(require __DIR__.'/catalog_stores.php'),
    ],
];