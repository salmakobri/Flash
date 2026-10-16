<?php
// Catalogues des magasins. Les PRIX (en MAD) sont des estimations : vérifiez-les
// sur les vrais menus, puis ajustez-les ici (c'est le seul endroit).
// 'meal' => true      : menu complet (livraison 15 MAD)
// 'type' => 'free_order' : le client écrit lui-même sa commande (pharmacie)

// Pizza Hut : [nom, description, prix Medium, prix Large]
$pizzas = [
    ['Margherita', 'Sauce tomate, mozzarella', 59, 79],
    ['Pepperoni', 'Pepperoni, mozzarella', 69, 89],
    ['Supreme', 'Viande, poivrons, oignons, champignons', 79, 99],
    ['BBQ Chicken', 'Poulet, sauce barbecue, oignons', 75, 95],
    ['Hot & Spicy', 'Viande épicée, piments, mozzarella', 75, 95],
    ['4 Fromages', 'Quatre fromages', 79, 99],
    ['Végétarienne', 'Légumes, mozzarella', 65, 85],
];
$pizzasM = array_map(fn ($p) => ['name' => 'Pizza '.$p[0].' (M)', 'desc' => $p[1], 'price' => $p[2], 'meal' => true], $pizzas);
$pizzasL = array_map(fn ($p) => ['name' => 'Pizza '.$p[0].' (L)', 'desc' => $p[1], 'price' => $p[3], 'meal' => true], $pizzas);

// O Tacos : [garniture, prix L, prix XL]
$tacos = [
    ['Poulet', 35, 45],
    ['Viande hachée', 35, 45],
    ['Cordon bleu', 40, 50],
    ['Nuggets', 38, 48],
    ['Tenders', 40, 50],
    ['Mixte', 42, 52],
];
$tacosL = array_map(fn ($t) => ['name' => 'Tacos '.$t[0].' (L)', 'price' => $t[1]], $tacos);
$tacosXL = array_map(fn ($t) => ['name' => 'Tacos '.$t[0].' (XL)', 'price' => $t[2]], $tacos);

// Supermarchés : [rayon, emoji, [[produit, prix Marjan, prix Carrefour], ...]]
$grocery = [
    ['Fruits & légumes', '🥕', [
        ['Tomates (1 kg)', 6, 7],
        ['Pommes de terre (1 kg)', 6, 6],
        ['Oignons (1 kg)', 5, 5],
        ['Carottes (1 kg)', 5, 6],
        ['Bananes (1 kg)', 14, 15],
        ['Pommes (1 kg)', 15, 16],
        ['Oranges (1 kg)', 7, 8],
        ['Citrons (1 kg)', 12, 12],
    ]],
    ['Produits laitiers & œufs', '🥛', [
        ['Lait demi-écrémé (1 L)', 8, 8],
        ['Yaourt nature (pack de 4)', 12, 13],
        ['Fromage en portions (x8)', 12, 13],
        ['Beurre (250 g)', 20, 21],
        ['Œufs (boîte de 12)', 18, 19],
    ]],
    ['Épicerie', '🛒', [
        ['Sucre (1 kg)', 8, 8],
        ['Farine (1 kg)', 7, 8],
        ['Riz (1 kg)', 16, 17],
        ['Spaghetti (500 g)', 7, 7],
        ['Huile de table (1 L)', 19, 20],
        ["Huile d'olive (1 L)", 70, 72],
        ['Thé vert (200 g)', 20, 22],
        ['Café moulu (250 g)', 30, 32],
        ['Thon en conserve (160 g)', 15, 16],
        ['Sel fin (1 kg)', 3, 3],
    ]],
    ['Boissons', '🥤', [
        ['Eau minérale (1,5 L)', 6, 6],
        ['Eau minérale (pack de 6 x 1,5 L)', 33, 34],
        ['Coca-Cola (1 L)', 10, 10],
        ["Jus d'orange (1 L)", 14, 15],
    ]],
    ['Hygiène & entretien', '🧼', [
        ['Savon (125 g)', 6, 6],
        ['Shampoing (400 ml)', 28, 30],
        ['Dentifrice (75 ml)', 15, 16],
        ['Papier toilette (6 rouleaux)', 22, 24],
        ['Liquide vaisselle (750 ml)', 16, 17],
        ['Eau de Javel (1 L)', 6, 7],
        ['Lessive en poudre (1 kg)', 35, 36],
    ]],
];
$groceryFor = fn (int $col) => array_map(fn ($s) => [
    'name' => $s[0],
    'emoji' => $s[1],
    'items' => array_map(fn ($i) => ['name' => $i[0], 'price' => $i[$col]], $s[2]),
], $grocery);

return [
    [
        'id' => 'kfc', 'name' => 'KFC', 'logo' => null,
        'options' => [
            ['name' => 'Menus', 'emoji' => '🍗', 'items' => [
                ['name' => 'Twister Combo', 'desc' => 'Sandwich Twister, frites, boisson', 'price' => 39, 'meal' => true],
                ['name' => 'Loaded Twister Combo', 'desc' => 'Loaded Twister, frites, boisson', 'price' => 45, 'meal' => true],
                ['name' => 'Twister Box', 'price' => 55, 'meal' => true],
                ['name' => 'Zinger Box', 'desc' => 'Zinger, 1 pièce ou 2 strips, frites, coleslaw, boisson', 'price' => 59, 'meal' => true],
                ['name' => 'Mighty Zinger Box', 'price' => 69, 'meal' => true],
                ['name' => 'Zinger Royal', 'desc' => 'Zinger, pièce de poulet, strip, coleslaw, boisson', 'price' => 75, 'meal' => true],
                ['name' => 'Zinger Star', 'desc' => 'Twister, Zinger, frites, boisson, 2 sauces', 'price' => 79, 'meal' => true],
            ]],
            ['name' => 'Buckets', 'emoji' => '🍗', 'items' => [
                ['name' => 'Bucket 8 pièces', 'price' => 99, 'meal' => true],
                ['name' => 'Bucket 12 pièces', 'price' => 139, 'meal' => true],
                ['name' => 'Captain Nacho Bucket 8 pièces', 'price' => 119, 'meal' => true],
                ['name' => 'Captain Nacho Bucket 12 pièces', 'price' => 159, 'meal' => true],
            ]],
            ['name' => 'Sandwichs', 'emoji' => '🌯', 'items' => [
                ['name' => 'Zinger Burger', 'price' => 32],
                ['name' => 'Twister', 'price' => 30],
                ['name' => 'Loaded Twister', 'price' => 36],
            ]],
            ['name' => 'Poulet', 'emoji' => '🍗', 'items' => [
                ['name' => 'Pièce de poulet', 'price' => 12],
                ['name' => 'Strips (x3)', 'price' => 28],
                ['name' => 'Hot wings (x6)', 'price' => 32],
            ]],
            ['name' => 'Accompagnements', 'emoji' => '🍟', 'items' => [
                ['name' => 'Frites', 'price' => 12],
                ['name' => 'Coleslaw', 'price' => 10],
                ['name' => 'Sauce BBQ', 'price' => 3],
                ['name' => 'Sauce Dynamite', 'price' => 3],
            ]],
            ['name' => 'Boissons', 'emoji' => '🥤', 'items' => [
                ['name' => 'Coca-Cola', 'price' => 12],
                ['name' => 'Sprite', 'price' => 12],
                ['name' => 'Eau', 'price' => 6],
            ]],
        ],
    ],
    [
        'id' => 'pizzahut', 'name' => 'Pizza Hut', 'logo' => null,
        'options' => [
            ['name' => 'Pizzas Medium', 'emoji' => '🍕', 'items' => $pizzasM],
            ['name' => 'Pizzas Large', 'emoji' => '🍕', 'items' => $pizzasL],
            ['name' => 'Entrées', 'emoji' => '🥖', 'items' => [
                ['name' => "Pain à l'ail", 'price' => 25],
                ['name' => 'Ailes de poulet (x6)', 'price' => 45],
            ]],
            ['name' => 'Pâtes', 'emoji' => '🍝', 'items' => [
                ['name' => 'Pâtes bolognaise', 'price' => 49, 'meal' => true],
                ['name' => 'Pâtes Alfredo', 'price' => 49, 'meal' => true],
                ['name' => 'Pâtes poulet', 'price' => 55, 'meal' => true],
            ]],
            ['name' => 'Desserts', 'emoji' => '🍫', 'items' => [
                ['name' => 'Brownie', 'price' => 25],
                ['name' => 'Cookie', 'price' => 15],
            ]],
            ['name' => 'Boissons', 'emoji' => '🥤', 'items' => [
                ['name' => 'Pepsi', 'price' => 12],
                ['name' => 'Eau', 'price' => 6],
            ]],
        ],
    ],
    [
        'id' => 'otacos', 'name' => 'O Tacos', 'logo' => null,
        'options' => [
            ['name' => 'Tacos L', 'emoji' => '🌮', 'items' => $tacosL],
            ['name' => 'Tacos XL', 'emoji' => '🌮', 'items' => $tacosXL],
            ['name' => 'Menus', 'emoji' => '🌮', 'items' => [
                ['name' => 'Menu Tacos L', 'desc' => 'Tacos L au choix, frites, boisson', 'price' => 55, 'meal' => true],
                ['name' => 'Menu Tacos XL', 'desc' => 'Tacos XL au choix, frites, boisson', 'price' => 65, 'meal' => true],
                ['name' => 'Menu Duo', 'desc' => '2 Tacos L, frites, 2 boissons', 'price' => 99, 'meal' => true],
                ['name' => "O'Mini Nuggets", 'desc' => '4 nuggets, frites, boisson', 'price' => 35, 'meal' => true],
            ]],
            ['name' => 'Snacks', 'emoji' => '🍗', 'items' => [
                ['name' => 'Nuggets (x4)', 'price' => 20],
                ['name' => 'Nuggets (x6)', 'price' => 28],
                ['name' => 'Jalapeño & cheese nuggets (x4)', 'price' => 25],
                ['name' => 'Jalapeño & cheese nuggets (x6)', 'price' => 35],
                ['name' => 'Tenders de poulet (x2)', 'price' => 25],
                ['name' => 'Frites', 'price' => 12],
            ]],
            ['name' => 'Boissons', 'emoji' => '🥤', 'items' => [
                ['name' => 'Canette', 'price' => 10],
                ['name' => 'Eau', 'price' => 6],
            ]],
        ],
    ],
    ['id' => 'marjan', 'name' => 'Marjan', 'logo' => null, 'options' => $groceryFor(1)],
    ['id' => 'carrefour', 'name' => 'Carrefour', 'logo' => null, 'options' => $groceryFor(2)],
    [
        // Pas de menu : le client écrit ce dont il a besoin
        'id' => 'pharmacie', 'name' => 'Pharmacie', 'logo' => null,
        'type' => 'free_order', 'options' => [],
    ],
];