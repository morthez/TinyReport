<?php
	class report{
    // Get data from array, and print it nicly as a table.
    var $tableheaders 	= '';
    var $content 		= '';
    var $dataRendered 	= '';
    var $dataToRender	= '';
       
	function render($resultSet){
		/*
		while ($this->dataToRender = mssql_fetch_object($resultSet)) {
            $array[] = $this->dataToRender;
			}
		*/
		while (	array_push($this->dataToRender, mssql_fetch_row($resultSet)));
		
		
		if(!$array) {
		$this->errorMsg .= 'dataToRender is not set!';
		}
		else {
		$this->dataRendered = ('<table id=report border=1>');
		$i = 0;
		foreach ($array as $row){ 
			if($i = 0){
				foreach ($row as $key => $value) {
				$this->dataRendered .= ('<th>' . $key . '</th>');
				}
			$i = $i++;
			}
		}
		foreach ($array as $row){
			$this->dataRendered .= "<tr>";
			foreach ($row as $column){
				$this->dataRendered .= ("<td>" . $column . "</td>");
			}
			$this->dataRendered .= ("</tr>");
		}
		$this->dataRendered .= ("</table>");
		return $this->dataRendered;
		}
	}
}
?>