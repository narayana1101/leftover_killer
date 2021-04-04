<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . "/../classes/getIngredients.php";


/** 
 * @covers \GetIngredients
 * */

class GetIngredientsTest extends TestCase
{

    protected static $RecipeModel;

    protected function setUp(): void
    {
        // please change it as your local or remote
        $servername = '18.222.31.30';
        $username = 'phpclient';
        $password = 'leftoverkillerphp';
        $dbname = 'leftover_killer';
        self::$RecipeModel = new GetIngredients($servername, $username, $password, $dbname);
    }

    public function tearDown(): void
    {
        self::$RecipeModel = null;
    }




    /** 
     * @covers \GetIngredients::process_query
    */
    public function testProcessQuery(): void
    {
        $result =  array();
        $result = self::$RecipeModel->process_query();
        
        $ingredient_id = 1;
        $ingredient_name = "salt";

        $this->assertEquals($ingredient_id, $result["ingredients"][0]["ingredient_id"]);
        $this->assertEquals($ingredient_name, $result["ingredients"][0]["ingredient_name"]);

        $ingredient_id = 2;
        $ingredient_name = "paprika";


        $this->assertEquals($ingredient_id, $result["ingredients"][1]["ingredient_id"]);
        $this->assertEquals($ingredient_name, $result["ingredients"][1]["ingredient_name"]);


    }

}
?>