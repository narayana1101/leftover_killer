<?php
use PHPUnit\Framework\TestCase;


require __DIR__ . "/../classes/getRecipes.php";

class GetRecipesTest extends TestCase
{

    protected static $RecipeModel;

    protected function setUp(): void
    {
        // please change it as your local or remote
        $servername = '';
        $username = '';
        $password = '';
        $dbname = '';
        self::$RecipeModel = new GetRecipes($servername, $username, $password, $dbname);
    }

    public function tearDown(): void
    {
        self::$RecipeModel = null;
    }





    public function testProcessQuery(): void
    {
        $result = self::$RecipeModel->process_query();
        
        $sql = "SELECT recipe_id, recipe_name, imageURL from recipe ORDER BY popularity DESC";
        $response= array();
        $stmt = self::$RecipeModel->stmt_init();
        $stmt = self::$RecipeModel->prepare($sql);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result > 0) {

            $response["success"] = true;
            $response["recipes"] = array();
            while($row = $result->fetch_assoc()) {
        
                $recipe = array();
                $recipe["recipe_id"] = $row["recipe_id"];
                $recipe["recipe_name"] = $row["recipe_name"];
                $recipe["img_url"] = $row["imageURL"];
        
                array_push($response["recipes"], $recipe);
            }
        } 
        else{
            $response["success"] = false;	
        }

        $this->assertEquals($response, $result);


    }


}
?>