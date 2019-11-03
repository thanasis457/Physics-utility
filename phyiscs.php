<?php
	session_start();

	$serverName="DESKTOP-KBTMVRA\\SQLEXPRESS";
	$connectionOptions= array("Database" => "Phyiscs","UID"=>"dkri", "PWD"=>"andros");
	
	$conn=sqlsrv_connect($serverName, $connectionOptions);


	/*$aaquery="SELECT MAX(problem_id) as result from variable";
	$aaresult=sqlsrv_query($conn, $aaquery);
	$aarow=sqlsrv_fetch_array($aaresult, SQLSRV_FETCH_ASSOC);
	$problem_id=$aarow['result']+1;*/

	if(isset($_POST['insert'])){
		$request=$_POST['request'];
		$problem=$_POST['problem'];
		$queryc="INSERT INTO problem(descryption) values('$problem')";
		$resultc=sqlsrv_query($conn, $queryc);
	}

	$querya="Select TOP(1) LAST_VALUE(problem_id) OVER (ORDER BY problem_id desc) as problem_id from problem";
	$resulta=sqlsrv_query($conn, $querya);
	$rowa=sqlsrv_fetch_array($resulta, SQLSRV_FETCH_ASSOC);
	$problem_id=$rowa['problem_id'];	

	$values=preg_split('/,/', $problem);
	$sizeval=count($values);
	$vars=array();
	for ($i=0; $i < $sizeval; $i++) { 
		$tempvars=preg_split('/=/', $values[$i]);
		for ($j=0; $j < 2; $j++) { 
			array_push($vars, $tempvars[$j]);
		}
	}
	$sizevars=count($vars);

	for ($i=0; $i < $sizevars; $i+=2) { 
		$variable=$vars[$i];
		$value=$vars[$i+1];
		$queryb="INSERT INTO variable(problem_id, request, variable, value) values('$problem_id', '$request', '$variable', '$value')";	 
		$resultb=sqlsrv_query($conn, $queryb);
	}
	$querytrim="UPDATE variable ";
	$querytrim=$querytrim."set variable=TRIM(variable) where problem_id='".$problem_id."'";
	$resulttrim=sqlsrv_query($conn, $querytrim);

function search_formula($conn, $prev_request, $request, $problem_id){
	$sqlvar = "DECLARE @problem_id int SET @problem_id = '".$problem_id. "'";
	$query="SELECT formula_id, formula from formula WHERE formula_req = '" .$request. "'";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	
 	$res=sqlsrv_query($conn, $query, $params, $options);
	
	$met=sqlsrv_num_rows($res);
	if($met==0){
		//$_SESSION['noformulas']="No forms for this prob";
	}else{
		$formulas=array();
		$id=array();
		while($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)){
			array_push($id, $row['formula_id']);
			array_push($formulas, $row['formula']);
		}
	}
	for($i=0; $i< $met; $i++){
		$formula = $formulas[$i];
		$formula_id=$id[$i];
		$sqlStmt= $sqlvar." SELECT (".$formula.") as result";
		//echo $sqlStmt;
		$re=sqlsrv_query($conn, $sqlStmt);
		$roww= sqlsrv_fetch_array($re, SQLSRV_FETCH_ASSOC);
		if(is_null($roww['result'])){	
			$sql = "declare @vars as varchar(max);";
			$sql = $sql."set @vars=(SELECT variables from formula where formula_id='" .$id[$i]. "');";
			$sql = $sql."SELECT Value FROM STRING_SPLIT(@vars, ',')";
			//echo $sql;
			$resu=sqlsrv_query($conn, $sql, array(), $options);
	
			$var=array();
			while($rowww=sqlsrv_fetch_array($resu, SQLSRV_FETCH_ASSOC)){
			$hi= $rowww['Value'];
			array_push($var, $rowww['Value']);
			}
			$count=count($var);
			$notnuls=array();
			$counter=0;
			for($x=0; $x<$count; $x++){
				$sqlquery="SELECT variable FROM variable where variable= '" . $var[$x] ."' AND problem_id='" .$problem_id. "'";
				//echo $sqlquery;
				$reslu=sqlsrv_query($conn, $sqlquery, array(), $options);		
				$roow=sqlsrv_fetch_array($reslu);
				if(is_null($roow['variable'])){
					$sqlformquery="SELECT formula from formula where formula_req= '". $var[$x]. "'";
					$reslut=sqlsrv_query($conn, $sqlformquery);
					$rooww=sqlsrv_fetch_array($reslut);
					if(!is_null($rooww['formula'])){
						search_formula($conn, $request, $var[$x], $problem_id);
					}
				}
				$querry="SELECT variable FROM variable where variable= '" . $var[$x] ."' AND problem_id='" .$problem_id. "'";
				$rres=sqlsrv_query($conn, $querry);
				$rrow=sqlsrv_fetch_array($rres, SQLSRV_FETCH_ASSOC);

				if(!is_null($rrow['variable'])){
					$counter++;
				}
			}
			if($counter==$count){
				search_formula($conn, $request, $request, $problem_id);
			}
		}
		if(!is_null($roww['result'])){
			$val=$roww['result'];
			$qu="INSERT INTO variable(problem_id, request, variable, value) VALUES ('$problem_id', '$request', '$request', '$val')";
			$resuu=sqlsrv_query($conn, $qu);
			$_SESSION['value']=$val;
		}
		$qqquery="Select formula_type, formula_req from formula where formula_id='".$formula_id."'";
		$rrresult=sqlsrv_query($conn, $qqquery);
		$rrrow=sqlsrv_fetch_array($rrresult, SQLSRV_FETCH_ASSOC);
		echo $rrrow['formula_req']."=".$rrrow['formula_type'];
		echo "<br>";
	}	
}

search_formula($conn, $request, $request, $problem_id);

echo $_SESSION['value'];

?>