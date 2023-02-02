<?php

ob_start(); 
include "main.php";
require_once('dbconnect.php');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/ExcessCashDetails.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

 $conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");


 //variables

$START_DATE = $_GET['START_DATE'];
$END_DATE = $_GET['END_DATE'];
$BRANCH =$_GET['BRANCH'];
$TRAN = "'D'";
$TRAN1 = "'GL'";
$AC_TYPE = "'10'";
$TYPE = "'CS'";
$STATUS ="'PS'";

$dateformate = "'DD/MM/YYYY'";



 $query =   'SELECT (COALESCE(CASE "AC_OP_CD"  WHEN '.$TRAN.' THEN  CAST("AC_OP_BAL" AS FLOAT)  ELSE (-1) * CAST("AC_OP_BAL" AS FLOAT) END,0) + 
             COALESCE(CAST(ACCOTRAN.TRAN_AMOUNT AS FLOAT),0) + COALESCE(CASHAMT.CASH_AMOUNT,0))CLOSING_BALANCE 
             FROM ACMASTER,
            (SELECT "TRAN_ACNOTYPE", "TRAN_ACTYPE", "TRAN_ACNO", COALESCE(SUM(CASE "TRAN_DRCR"  WHEN '.$TRAN.' THEN  CAST("TRAN_AMOUNT" AS FLOAT)  
            ELSE (-1) * CAST("TRAN_AMOUNT" AS FLOAT) END),0) TRAN_AMOUNT FROM ACCOTRAN WHERE "TRAN_ACNOTYPE" = '.$TRAN1.' 
            AND "TRAN_ACTYPE" ='.$AC_TYPE.' AND "TRAN_ACNO" = 1 AND "TRAN_DATE" <= ('.$END_DATE.') 
            AND NOT ( "TRAN_DATE" = ('.$END_DATE.') AND COALESCE(CAST("CLOSING_ENTRY" AS INTEGER),CAST(0 AS INTEGER)) <> 0 ) 
            GROUP BY "TRAN_ACNOTYPE", "TRAN_ACTYPE", "TRAN_ACNO") ACCOTRAN ,
            (SELECT '.$TRAN1.' "TRAN_ACNOTYPE", '.$AC_TYPE.' "TRAN_ACTYPE", 1 "TRAN_ACNO" ,(COALESCE(SUM(CASE "TRAN_DRCR"  WHEN '.$TRAN.' 
            THEN  (-1) * CAST("TRAN_AMOUNT" AS FLOAT)  ELSE CAST("TRAN_AMOUNT" AS FLOAT) END),0)) CASH_AMOUNT 
            FROM VWDETAILDAILYTRAN WHERE "TRAN_TYPE" = '.$TYPE.' 
            AND "TRAN_DATE" <= ('.$END_DATE.') AND "TRAN_STATUS" = '.$STATUS.' ) CASHAMT 
            Where ACMASTER."AC_ACNOTYPE"  = ACCOTRAN."TRAN_ACNOTYPE"                  
            AND ACMASTER."AC_TYPE"  = CAST(ACCOTRAN."TRAN_ACTYPE" AS INTEGER)			
            AND ACMASTER."AC_NO" =  ACCOTRAN."TRAN_ACNO"
            AND ACMASTER."AC_ACNOTYPE"  = CASHAMT."TRAN_ACNOTYPE"    
            AND ACMASTER."AC_TYPE"  = CAST(CASHAMT."TRAN_ACTYPE" AS INTEGER)					
            AND ACMASTER."AC_NO" =  CASHAMT."TRAN_ACNO"
            AND ACMASTER."AC_ACNOTYPE"  = '.$TRAN1.'
            AND ACMASTER."AC_TYPE" = '.$AC_TYPE.'
            AND ACMASTER."AC_NO" = 1 ';

            
$query1 = "SELECT SANCTIONED_CASH_LIMIT FROM SYSPARA";        

         // echo $query; 

          
$sql =  pg_query($conn,$query);
$sql =  pg_query($conn,$query1);

      //echo $sql;
$i = 0;
while($row = pg_fetch_assoc($sql))
{  

     $tmp=[
        'SANCTIONED_CASH_LIMIT' => $row['SANCTIONED_CASH_LIMIT'],
        'TRAN_DATE' => $row['TRAN_DATE'],
        'TRAN_AMOUNT' => $row['closing_balance'],
        'START_DATE'=> $START_DATE,
        'END_DATE' => $END_DATE,
        'BRANCH' => $BRANCH,
       
       
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

