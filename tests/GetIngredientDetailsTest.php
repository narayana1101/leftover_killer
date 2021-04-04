<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . "/../classes/getIngredientDetails.php";

/** 
 * @covers \GetIngredientDetails
 * */

class GetIngredientDetailsTest extends TestCase
{

    protected static $RecipeModel;
    protected static $conn;

    
    protected function setUp(): void
    {
        // please change it as your local or remote
        $servername = '18.222.31.30';
        $username = 'phpclient';
        $password = 'leftoverkillerphp';
        $dbname = 'leftover_killer';
        self::$RecipeModel = new GetIngredientDetails($servername, $username, $password, $dbname);
        self::$conn = mysqli_connect($servername, $username, $password, $dbname);
    }

    public function tearDown(): void
    {
        self::$RecipeModel = null;
    }




    /*
    * @covers \GetIngredientDetails::processIngredientDetails
    */

    public function testprocessIngredientDetails(): void
    {
        $ingredient_id = 1;
        $ingredient_name = "salt";
        $image_URL = null;
        $result = array();


        $result = self::$RecipeModel->processIngredientDetails($ingredient_id, $result);
        $this->assertEquals($ingredient_name, $result["ingredient_name"]);
        $this->assertEquals($image_URL, $result["image_URL"]);
    }

    /*
    * @covers \GetIngredientDetails::testprocessIngredientDetailsFailed
    */

    public function testprocessIngredientDetailsFailed(): void
    {
        $ingredient_id = 600;

        $result = array();


        $result = self::$RecipeModel->processIngredientDetails($ingredient_id, $result);
        $this->assertEquals("There is no this ingredient", $result["error"]);
    }




   /*
    * @covers \GetIngredientDetails::topRecipesWithIngredient
    */

    
    public function testTopRecipesWithIngredient(): void
    {
        $result = array();
        $expected = array();
        $ingredient = 2;
        $result = self::$RecipeModel->TopRecipesWithIngredient($ingredient, $result);
        
        $sql_select_recipe_top = "SELECT recipe.recipe_id, recipe.popularity, recipe.imageURL,
        recipe.recipe_name, recipe_ingredient.ingredient_id
        FROM recipe INNER JOIN recipe_ingredient
        ON recipe.recipe_id = recipe_ingredient.recipe_id
        WHERE ingredient_id = '$ingredient'
        ORDER BY popularity DESC";


        $response = mysqli_query(self::$conn, $sql_select_recipe_top);
        $counter = 0;
        $expected["Top_recipes"] = array();
        while (($row = $response->fetch_assoc()) && $counter < 5) {
            $Recipe = array();

            $Recipe['recipe_id'] = $row['recipe_id'];
            $Recipe['recipe_name'] = $row['recipe_name'];
            $Recipe['img_url'] = $row['imageURL'];
            array_push($expected["Top_recipes"], $Recipe);
            ++$counter;
        }

        $this->assertEquals($expected, $result);

    }


    

}

?>