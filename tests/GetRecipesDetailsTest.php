<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . "/../classes/getRecipeDetails.php";


/** 
 * @covers \GetRecipeDetails
 * */

class GetRecipesDetailsTest extends TestCase
{

    protected static $RecipeModel;


    protected function setUp(): void
    {
        // please change it as your local or remote
        $servername = '18.222.31.30';
        $username = 'phpclient';
        $password = 'leftoverkillerphp';
        $dbname = 'leftover_killer';
        self::$RecipeModel = new GetRecipeDetails($servername, $username, $password, $dbname);
    }

    public function tearDown(): void
    {
        self::$RecipeModel = null;
    }




    /**  
     * @covers \GetRecipeDetails::processRecipeInfo
     * */
    public function testProcessRecipeInfo(): void
    {

        $actual_recipe_id = 1;
        $actual_recipe_name = "Cardamom Maple Salmon";
        $actual_recipe_img = "https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fimages.media-allrecipes.com%2Fuserphotos%2F5375740.jpg&w=596&h=596&c=sc&poi=face&q=85";
        $recipes_list = self::$RecipeModel->processRecipeInfo($actual_recipe_id);

        $this->assertEquals($actual_recipe_id, $recipes_list["recipe_id"]);
        $this->assertEquals($actual_recipe_name, $recipes_list["recipe_name"]);
        $this->assertEquals($actual_recipe_img, $recipes_list["imageURL"]);
    }

    /** 
     * @covers \GetRecipeDetails::processIngredientInfo 
     * */
    public function testIngredient(): void
    {
        $recipes_list = array();
        $actual_recipe_id = 1;
        
        $recipes_list = self::$RecipeModel->processIngredientInfo($actual_recipe_id, $recipes_list);

        $Ingredient_id = 1;
        $Ingredient_name = "salt";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][0]["ingredient_id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][0]["ingredient_name"]);

        $Ingredient_id = 2;
        $Ingredient_name = "paprika";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][1]["ingredient_id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][1]["ingredient_name"]);

        $Ingredient_id = 3;
        $Ingredient_name = "ground cardamom";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][2]["ingredient_id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][2]["ingredient_name"]);
    
        $Ingredient_id = 4;
        $Ingredient_name = "ground coriander";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][3]["ingredient_id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][3]["ingredient_name"]);

        $Ingredient_id = 5;
        $Ingredient_name = "black pepper";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][4]["ingredient_id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][4]["ingredient_name"]);

        $Ingredient_id = 6;
        $Ingredient_name = "grapeseed oil";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][5]["ingredient_id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][5]["ingredient_name"]);

        
        $Ingredient_id = 7;
        $Ingredient_name = "maple syrup";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][6]["ingredient_id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][6]["ingredient_name"]);

        $Ingredient_id = 8;
        $Ingredient_name = "salmon fillet";

        $this->assertEquals($Ingredient_id, $recipes_list["ingredients"][7]["ingredient_id"]);
        $this->assertEquals($Ingredient_name, $recipes_list["ingredients"][7]["ingredient_name"]);
    }
}
?>