<?php
use PHPUnit\Framework\TestCase;


require __DIR__ . "/../classes/getRecipes.php";
/** 
 * @covers \GetRecipes
 * */


class GetRecipesTest extends TestCase
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
        self::$RecipeModel = new GetRecipes($servername, $username, $password, $dbname);
        self::$conn = mysqli_connect($servername, $username, $password, $dbname);
    }

    public function tearDown(): void
    {
        self::$RecipeModel = null;
    }




    /**
     *  @covers  \GetRecipes::process_query() 
     * */
    public function testGetRecipesNumber(): void
    {
        $result = array();
        $result = self::$RecipeModel->process_query();
        
        $sql = "SELECT recipe_id, recipe_name, imageURL from recipe";


        $response = mysqli_query(self::$conn, $sql);

        $this->assertEquals(mysqli_num_rows($response), count($result["recipes"]));


    }


}
?>