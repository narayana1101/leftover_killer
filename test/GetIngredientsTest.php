<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . "/../classes/getIngredients.php";

class GetIngredientsTest extends TestCase
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




    /** @covers */
    public function testProcessQuery(): void
    {
        $result = self::$RecipeModel->process_query();
        
        $sql = "SELECT * FROM ingredient";
        $response= array();
        $stmt = self::$RecipeModel->stmt_init();
        $stmt = self::$RecipeModel->prepare($sql);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result > 0) {	
            $response["success"] = true;
            $response["ingredients"] = array();
            while($row = $result->fetch_assoc()) {
                $Ingredient = array();
                $Ingredient["id"] = $row["ingredient_id"];
                $Ingredient["name"] = $row["ingredient_name"];
                $Ingredient["image_url"] = $row["imageURL"];
                array_push($response["ingredients"], $Ingredient);
            }
        } 
        else{
            $response["success"] = false;	
        }

        $this->assertEquals($response, $result);


    }


}
?>