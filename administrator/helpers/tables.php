<?
class Tables{
	// аналог: $fields=array_keys($table->getFields());
	public static function getTableFields($table_name){
		$db=JFactory::getDBO();
		$query="DESC #__".$table_name;
		$db->setQuery($query);
		$fields=$db->loadResultArray();
		echo "<div class=''>fields= ".$fields."</div>";
	}
}
?>