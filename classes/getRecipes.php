<?php
// references: https://stackoverflow.com/questions/17226762/mysqli-bind-param-for-array-of-strings
// get_recipes class


class GetRecipes
{
	public static $database;

	public function __construct($servername, $username, $password, $dbname){
		self::$database = new mysqli($servername, $username, $password, $dbname);
	}

	public function process_query(){
        $sql = "SELECT recipe_id, recipe_name, imageURL from recipe ORDER BY popularity DESC";
        $response= array();
        $stmt = self::$database->prepare($sql);

        $stmt->execute();
        $result = $stmt->get_result();


        $response["success"] = true;
        $response["recipes"] = array();
        while($row = $result->fetch_assoc()) {
        
            $recipe = array();
            $recipe["recipe_id"] = $row["recipe_id"];
            $recipe["recipe_name"] = $row["recipe_name"];
            $recipe["img_url"] = $row["imageURL"];
        
            array_push($response["recipes"], $recipe);
        }
         

        return $response;
    }

	
}
?>