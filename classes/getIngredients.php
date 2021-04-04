<?php
// references: https://stackoverflow.com/questions/17226762/mysqli-bind-param-for-array-of-strings
// get_ingredients class
class GetIngredients
{
	public static $database;

	public function __construct($servername, $username, $password, $dbname){
		self::$database = new mysqli($servername, $username, $password, $dbname);
	}

	public function process_query(){
        
        $sql= "SELECT * FROM ingredient";
        $response= array();

        $stmt = self::$database->prepare($sql);

        $stmt->execute();
        $result = $stmt->get_result();

        $response["success"] = true;
        $response["ingredients"] = array();
        while($row = $result->fetch_assoc()) {
            $Ingredient = array();
            $Ingredient["ingredient_id"] = $row["ingredient_id"];
            $Ingredient["ingredient_name"] = $row["ingredient_name"];
            $Ingredient["image_url"] = $row["imageURL"];
            array_push($response["ingredients"], $Ingredient);
        }
        
        
        return $response;
    }

	
}
?>