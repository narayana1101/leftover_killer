--create database if not exists leftoverkiller ;

--use leftoverkiller;

create table if not exists recipe (
	recipe_id int auto_increment not null  primary key,
    recipe_name varchar(100) not null,
    popularity int,
    imageURL text(255),
    instruction text(255)
);

create table if not exists ingredient (
	ingredient_id int auto_increment not null primary key,
    ingredient_name varchar(100) not null,
    imageURL text(255)
);

create table if not exists recipe_ingredient (
	recipe_id int,
    ingredient_id int,
    constraint fk_recipeIngredient_recipeId foreign key (recipe_id) references recipe(recipe_id) on delete cascade on update cascade,
    constraint fk_recipeIngredient_ingredientId foreign key (ingredient_id) references ingredient(ingredient_id) on delete cascade on update cascade
);

-- drop database leftoverkiller;