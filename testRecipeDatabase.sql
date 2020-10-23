use chain_gang;
-- insert into recipe(recipe_name) values ("test");
insert into recipe(recipe_name) values  ("Cardamom Maple Salmon"), ("Salmon Tango"), ("Delicious Salmon"), ("WOW Salmon"), ("Garlic Steak with Garlic"),
("Rosemary Steak"), ("Argentinean Skirt Steaks"), ("Steak with Salmon");

insert into ingredient(ingredient_name) values 
("salt"), ("paprika"), ("ground cardamom"), ("ground coriander"), ("ground black pepper"), ("grapeseed oil"), ("maple syrup"), ("salmon fillet"),
("butter"), ("brown sugar"), ("soy sauce"), ("lemon juice"), ("white wine"),
("garlic"), ("olive oil"), ("New York strip steaks"), ("garlic"), ("balsamic vinegar"),
("red wine"), ("fresh rosemary"),
("adobo seasoning");

insert into recipe_ingredient(recipe_id, ingredient_id) values 
(1,1), (1,2), (1,3), (1,4), (1,5), (1,6), (1,7), (1,8),
(2,8), (2,9),(2,10),(2,11), (2,12), (2,13),
(3,1), (3,5), (3, 6), (3,9), (3, 10), (3, 8),
(4,3), (4,8), (4,9), (4,10), (4, 8),
(5,1), (5,5),(5,14), (5,15),(5,16), (5,17), (5,18),
(6,1), (6,16), (6,19), (6, 20),
(7,1), (7,9), (7,15), (7, 16),
(8,1), (8,8), (8,9), (8,16);