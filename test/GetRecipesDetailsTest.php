<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . "/../classes/getRecipeDetails.php";

class GetRecipesDetailsTest extends TestCase
{

    protected static $RecipeModel;

    protected function setUp(): void
    {
        // please change it as your local or remote
        $servername = '';
        $username = '';
        $password = '';
        $dbname = '';
        self::$RecipeModel = new Recipe($servername, $username, $password, $dbname);
    }

    public function tearDown(): void
    {
        self::$RecipeModel = null;
    }

    public function arrays_are_similar($a, $b)
    {
        // references: https://stackoverflow.com/questions/3838288/phpunit-assert-two-arrays-are-equal-but-order-of-elements-not-important
        // if the indexes don't match, return immediately
        if (count(array_diff_assoc($a, $b))) {
            return false;
        }
        // we know that the indexes, but maybe not values, match.
        // compare the values between the two arrays
        foreach ($a as $k => $v) {
            if ($v !== $b[$k]) {
                return false;
            }
        }
        // we have identical indexes, and no unequal values
        return true;
    }



    /** @covers */
    public function testProcessRecipeInfo(): void
    {

        $actual_recipe_id = 1;
        $actual_recipe_name = "cardamom maple salmon";
        $actual_recipe_img = "https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fimages.media-allrecipes.com%2Fuserphotos%2F5375740.jpg&w=596&h=596&c=sc&poi=face&q=85";
        $recipes_list = self::$RecipeModel->processIngredientInfo($actual_recipe_id );

        $this->assertEquals($actual_recipe_id, $recipes_list[0]["recipe_id"]);
        $this->assertEquals($actual_recipe_name, $recipes_list[0]["recipe_name"]);
        $this->assertEquals($actual_recipe_img, $recipes_list[0]["img_url"]);
    }

    /** @covers */
    public function testIngredient(): void
    {
        $response = array();
        $actual_recipe_id = 1;
        
        $recipes_list = self::$RecipeModel->processIngredientInfo($actual_recipe_id, $response);

        $Ingredient_id = 5;
        $Ingredient_name = "black pepper";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][0]["id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][0]["name"]);

        $Ingredient_id = 6;
        $Ingredient_name = "grapeseed oil";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][1]["id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][1]["name"]);

        $Ingredient_id = 3;
        $Ingredient_name = "ground cardamom";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][2]["id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][2]["name"]);
    
        $Ingredient_id = 4;
        $Ingredient_name = "ground coriander";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][3]["id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][3]["name"]);

        $Ingredient_id = 7;
        $Ingredient_name = "maple syrup";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][4]["id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][4]["name"]);

        $Ingredient_id = 2;
        $Ingredient_name = "paprika";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][5]["id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][5]["name"]);

        
        $Ingredient_id = 8;
        $Ingredient_name = "salmon fillet";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][6]["id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][6]["name"]);

        $Ingredient_id = 1;
        $Ingredient_name = "salt";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][7]["id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][7]["name"]);
    }
}
?>