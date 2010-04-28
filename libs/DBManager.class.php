<?php

class DataBaseException extends Exception {}
class DBManager{

    var $connection   = '';
    var $queryCounter = 0;
    var $totalTime    = 0;    
    var $errorMsg     = '';
    var $resultSet    = '';
	var $repConf;
	var $repSelect		=array();
	var $repData		=array();
	var $StaticSpResult =array();
	
	/////////////////////////////
	// Upon init. of the class. This enables the connection to the database server. 
	// And selects the database we are going to use.
	/////////////////////////////
	function __construct ($serverName, $dbuser, $dbpass, $database){
        $startTime = $this->getMicroTime();
        
        // Try to make a connection to the server
        if (!$this->connection = @mssql_connect($serverName, $dbuser, $dbpass,true)){
            $this->errorMsg  = mssql_get_last_message();
            return false;
        }

        // Now select the database
        if (!@mssql_select_db($database,$this->connection)){
            $this->errorMsg  = mssql_get_last_message();
            @mssql_close($this->connection);
            return false;
        }
			
	// mssql_free_result( $this->resultSet );
	
	$this->totalTime += $this->getMicroTime() - $startTime;

	}

	/////////////////////////////
	// Get report selection
	/////////////////////////////
	function ReportSelect() {
		$this->executeSpStatic('MD_SP_SelectReport_All', &$this->repSelect);
	}
	
	/////////////////////////////
	// Get report configuration
	/////////////////////////////
	function ReportConfig($reportId) {
		$startTime = $this->getMicroTime();
		
		++$this->queryCounter;

$query=<<<EOF
SELECT 
	ReportId, 
	ReportName, 
	CatName, 
	TemplatePath, 
	StoredProcedureName,
	Style,
	IdPdvlist
FROM 
	dbo.MD_RepSys_ReportName 
	INNER JOIN dbo.MD_RepSys_Category 
	ON dbo.MD_RepSys_ReportName.CatId = dbo.MD_RepSys_Category.CatId 
WHERE 
	Disabled !=1 
	AND dbo.MD_RepSys_ReportName.ReportId = $reportId

EOF;

		$sp_parms->ReportId		= $reportId;
		$result = $this->executeStoredprocedure('MD_SP_GetReportConfig_x_ReportId', $sp_parms);

		if(isset ($result)){
			while ($row = mssql_fetch_object( $result )) {
				$this->repConf = $row;
			}
		}
	
	}
	/////////////////////////////
	// Generate the data for the report (aka prep data)
	/////////////////////////////
	function ReportGenerate($sp_parms, $resultto = '$this->repData'){
	$this->executeStoredprocedure($this->repConf->StoredProcedureName, $sp_parms);
	if ($this->getSelectedRows($this->resultSet) == 0){ 
		throw new DataBaseException("Spiacente. Non ci sono risultati per questa selezione.");	
	} else {
		while ($data = mssql_fetch_row($this->resultSet)) {
				array_push($this->repData, $data); 
			}; 
		}
	}
	
	/////////////////////////////
	// Execute StoredProcedure with no extra
	// variables (aka: view-only)
	/////////////////////////////
	function executeSpStatic($StoredProcedureName, &$resultto = null){
		// If the 2nd parameter is defined, it will default to $this->StaticSpResult.
		if($resultto === null) {$resultto = &$this->StaticSpResult;}
		$startTime = $this->getMicroTime();
		
		++$this->queryCounter;
		
		if(!$this->stmt = mssql_init($StoredProcedureName, $this->connection)){
				throw new DataBaseException(mssql_get_last_message());
			}
		
		if(!$result = @mssql_execute($this->stmt)){
			throw new DataBaseException(mssql_get_last_message());
			$this->totalTime = $this->getMicroTime() - $startTime;
			return true;
		}

		if ($this->getSelectedRows($result) == 0){ 
		throw new DataBaseException($error_noresult);	
		}
			
		else {
			while ($data = mssql_fetch_row($result)) {
					array_push($resultto, $data); 
			};
		}
	}
	
	/*
	*	Execute a StoredProcedure. 
	*	
	*	@param string $storedproc
	*	@param object $sp_parms
	*	@return mixed
	**/
	function executeStoredprocedure($storedproc, $sp_parms){
        $startTime = $this->getMicroTime();

        ++$this->queryCounter;
        
		if(!$this->stmt 	= mssql_init($storedproc, $this->connection)){
			$this->errorMsg	.= mssql_get_last_message();
		}

		foreach ($sp_parms as $key=>$parm) {
			if(!mssql_bind($this->stmt, '@'.$key, $sp_parms->$key, SQLVARCHAR)) {
				throw new DataBaseException('Unable to bind $sp_name:$key.. This is not good!<br>'.mssql_get_last_message);
			}			
		}
		
		
        if(!$this->resultSet = @mssql_execute($this->stmt)){
			throw new DataBaseException(mssql_get_last_message());
            $this->totalTime = $this->getMicroTime() - $startTime;
            return false;
        }
        
        $this->totalTime += $this->getMicroTime() - $startTime;

        return $this->resultSet;
    }
	
	/*
	*	Get the name of the selected PuntoVendita. 
	*	
	*	@param string $String 
	*	@return mixed
	**/
	function getIdpdv ($id) {
		$sp_parms->IdPdv = $id;
		$result = $this->executeStoredprocedure('MD_SP_GetReportIdPdv_Name', $sp_parms);
		
		if(isset ($result)){
			while ($row = mssql_fetch_object( $result )) {
				$this->idpdv_name = $row;
			}
		}
		return ($this->idpdv_name->IDPdv .' - '. $this->idpdv_name->Denominazione );
	}
	
	
	/////////////////////////////
	// Get affected rows
	/////////////////////////////
	function getAffectedRows($result)
    {
        return mssql_affected_rows($result);
    }    
	
	/////////////////////////////
	// Get selected rows
	/////////////////////////////
	function getSelectedRows($result)
    {
        return mssql_num_rows($result);
    }
	
	/////////////////////////////
	// Get the result set, and put it in a array we can use.
	/////////////////////////////
	protected function loadResult() {
        $array = array();
        while ($row = mssql_fetch_object( $this->resultSet )) {
			$array[] = $row;
        }
        mssql_free_result( $this->resultSet );

        return $this->dataToRender;
    } 
	
	/////////////////////////////
	// Get the execution time for the querys
	/////////////////////////////
 	function getDBTime(){
        return round($this->totalTime,6);
    }
	
    /////////////////////////////
	// check how many querys that where ran.
	/////////////////////////////
    function getSqlCount(){
        return $this->queryCounter;
    }
	
	/////////////////////////////
	// Timer function
	/////////////////////////////
    function getMicroTime() {
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
    } 
}
?>
