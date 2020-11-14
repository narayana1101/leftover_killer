<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . "/../classes/recipe.php";

/** 
 * @covers \Recipe
 * */

class GetMatchingRecipeTest extends TestCase
{

    protected static $RecipeModel;


    
    protected function setUp(): void
    {
        // please change it as your local or remote
        $servername = '18.222.31.30';
        $username = 'phpclient';
        $password = 'leftoverkillerphp';
        $dbname = 'leftover_killer';
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

    /** 
     * @covers \Recipe::get_ingredient_id
     * */
    public function testGetIngredientID(): void
    {
        $ingredeint_list = ["salt"];
        $actual = [1];
        $result = self::$RecipeModel->get_ingredient_id($ingredeint_list);
        $this->assertEquals($actual, $result);
    }
    /** 
     * @covers  \Recipe::get_ingredient_id
     * */
    public function testGetIngredientByIdNotExist(): void
    {
        $ingredeint_list = ["lalala"];
        $actual = [];
        $result = self::$RecipeModel->get_ingredient_id($ingredeint_list);
        $this->assertEquals($actual, $result);
    }

    /**
     *  @covers \Recipe::get_ingredient_id
    */
    public function testGetIngredientByMutipleId(): void
    {
        $ingredeint_list = ["paprika", "salt"];
        $actual = [1, 2];
        $result = self::$RecipeModel->get_ingredient_id($ingredeint_list);
        $this->assertTrue(true, $this->arrays_are_similar($result, $actual));
        $this->assertTrue(count($result) == count($actual));

    }

    /** 
     * @covers \Recipe::get_recipe_id_with_ingredients
     * */
    public function testGetRecipeIdWithIngredients(): void
    {
        $ingredient_list = ["salt",
            "paprika",
            "ground cardamom",
            "ground coriander",
            "black pepper",
            "grapeseed oil",
            "maple syrup",
            "salmon fillet"];
        $ingredeint_id = self::$RecipeModel->get_ingredient_id($ingredient_list);
        $actual_recipe_id = 1;
        $actual_recipe_name = "Cardamom Maple Salmon";
        $actual_recipe_img = "https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fimages.media-allrecipes.com%2Fuserphotos%2F5375740.jpg&w=596&h=596&c=sc&poi=face&q=85";
        $recipes_list = self::$RecipeModel->get_recipe_id_with_ingredients($ingredeint_id);

        $this->assertEquals($actual_recipe_id, $recipes_list[0]["recipe_id"]);
        $this->assertEquals($actual_recipe_name, $recipes_list[0]["recipe_name"]);
        $this->assertEquals($actual_recipe_img, $recipes_list[0]["img_url"]);
    }
}

?>