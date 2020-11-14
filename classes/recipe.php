<?php
// references: https://stackoverflow.com/questions/17226762/mysqli-bind-param-for-array-of-strings
//get_matching_recipes class
class Recipe
{
	public static $database;

	public function __construct($servername, $username, $password, $dbname)
	{
		self::$database = new mysqli($servername, $username, $password, $dbname);
	}

	public function process_query($sql, $parameter, $parameter_type)
	{
		$ingredient_num = count($parameter);
		$stmt = self::$database->stmt_init(); // clear the result
		$stmt = self::$database->prepare($sql);
		$input_type = str_repeat($parameter_type, $ingredient_num);
		$stmt->bind_param($input_type, ...$parameter);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->free_result();
		return $result;
	}

	public function get_ingredient_id($ingredient_list)
	{
		$ingredients_id_array = array(); // return array

		$ingredient_num = count($ingredient_list);
		$sql_get_ingredient_id = "SELECT ingredient_id FROM ingredient WHERE ingredient_name in (";
		if ($ingredient_num > 1) {
			$sql_get_ingredient_id .= str_repeat("? ,", $ingredient_num - 1);
		}
		$sql_get_ingredient_id .= "?";
		$sql_get_ingredient_id .= ");";

		$result = $this->process_query($sql_get_ingredient_id, $ingredient_list, "s");

		while ($row = $result->fetch_assoc()) {
			array_push($ingredients_id_array, $row['ingredient_id']);
		}

		return $ingredients_id_array;
	}

	public function get_recipe_id_with_ingredients($ingredients_id)
	{
		$recipes_array = array();
		$ingredient_num = count($ingredients_id);
		$sql_select_recipe_by_recipe_id = "SELECT * FROM recipe WHERE recipe_id NOT IN (SELECT DISTINCT recipe_id FROM recipe_ingredient WHERE ingredient_id NOT IN (";
		if ($ingredient_num > 1) {
			$sql_select_recipe_by_recipe_id .= str_repeat("? ,", $ingredient_num - 1);
		}
		$sql_select_recipe_by_recipe_id .= "?";
		$sql_select_recipe_by_recipe_id .= "))";

		$result = $this->process_query($sql_select_recipe_by_recipe_id, $ingredients_id, "i");

		while ($row = $result->fetch_assoc()) {
			$recipe = array();
			$recipe['recipe_id'] = $row['recipe_id'];
			$recipe['recipe_name'] = $row['recipe_name'];
			$recipe['img_url'] = $row['imageURL'];
			array_push($recipes_array, $recipe);
		}
		return $recipes_array;
	}
}
?>
