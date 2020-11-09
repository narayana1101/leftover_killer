<?php
// references: https://stackoverflow.com/questions/17226762/mysqli-bind-param-for-array-of-strings
// get_ingredients class
class GetIngredients
{
	public static $database;

	public function __construct($servername, $username, $password, $dbname){
		self::$database = new mysqli($servername, $username, $password, $dbname);
	}

	public function process_query($sql){
        $response= array();
        $stmt = self::$database->stmt_init();
        $stmt = self::$database->prepare($sql);

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
        return $response;
    }

	
}
?>