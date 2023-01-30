<?php

ob_start(); 
include "main.php";
require_once('dbconnect.php');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/SpecialInstruction.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

$conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");

//Variables

// $startDate = "'19/01/2021'";
// $endDate = "'19/02/2022'";
// $dateformate = "'DD/MM/YYYY'";
 

$sdate = $_GET['sdate'];
$edate = $_GET['edate'];
$revoke = $_GET['revoke'];
$bankName = $_GET['bankName'];
$NAME  = $_GET['NAME'];


// $bankName = str_replace("'", "", $bankName);
// $sdate_ = str_replace("'", "", $sdate_);
// $edate_ = str_replace("'", "", $edate_);
// $branchName = str_replace("'", "", $branchName);


$query =' SELECT SPECIALINSTRUCTION."INSTRUCTION_DATE" , SPECIALINSTRUCTION."INSTRUCTION_NO" ,SPECIALINSTRUCTION."TRAN_ACNO" ,VWALLMASTER."ac_name", 
          SPECIALINSTRUCTION."FROM_DATE", SPECIALINSTRUCTION."TO_DATE",  SPECIALINSTRUCTION."SYSADD_LOGIN",SPECIALINSTRUCTION."SYSCHNG_LOGIN",
          SPECIALINSTRUCTION."DETAILS", SPECIALINSTRUCTION."REVOKE_DATE"  FROM  SPECIALINSTRUCTION 
          LEFT OUTER JOIN  VWALLMASTER ON SPECIALINSTRUCTION ."TRAN_ACNO" = VWALLMASTER."ac_no"
          WHERE CAST(SPECIALINSTRUCTION."INSTRUCTION_DATE" AS DATE) >= CAST('.$sdate.' AS DATE) 
          AND CAST(SPECIALINSTRUCTION."INSTRUCTION_DATE" AS DATE) <= CAST('.$edate.' AS DATE)';

        // echo $query;
          
$sql =  pg_query($conn,$query);

$i = 0;

while($row = pg_fetch_assoc($sql))
{ 

     $tmp=[
         'INSTRUCTION_NO' => $row['INSTRUCTION_NO'],
         'INSTRUCTION_DATE' => $row['INSTRUCTION_DATE'],
         'TRAN_ACNO' => $row['TRAN_ACNO'],
         'SYSADD_LOGIN' => $row['SYSADD_LOGIN'],
         'SYSCHNG_LOGIN' => $row['SYSCHNG_LOGIN'],
         'ACCOUNT_NAME' => $row['ac_name'],
         'DETAILS' => $row['DETAILS'],
         'FROM_DATE' => $row['FROM_DATE'],
         'TO_DATE' => $row['TO_DATE'],
         'sdate' => $sdate,
         'edate' => $edate,
         'revoke' => $revoke,
         'bankName' => $bankName,
         'NAME'  => $NAME,
       
     ];
    
    $data[$i]=$tmp;
    $i++;
    
}
ob_end_clean();

$config = ['driver'=>'array','data'=>$data];
//print_r($data);
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');


?>
