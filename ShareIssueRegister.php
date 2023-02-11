<?php

ob_start(); 
include "main.php";
require_once('dbconnect.php');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/ShareIssueRegister.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

 $conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");


 //variables

$START_DATE = $_GET['START_DATE'];
$END_DATE = $_GET['END_DATE'];
$AC_TYPE = $_GET['AC_TYPE'];    
$BRANCH = $_GET['BRANCH'];
$dateformate = "'DD/MM/YYYY'";



 $query = ' SELECT SHARETRAN."TRAN_DATE", SHMASTER."AC_NO", SHARETRAN."TRAN_AMOUNT", SHARETRAN."NO_OF_SHARES", 
            SHARETRAN."CERTIFICATE_NO", SHARETRAN."SHARES_FROM_NO", SHARETRAN."SHARES_TO_NO", SHARETRAN."SHARES_TRANSFER_DATE", 
            SHARETRAN."SHARES_RETURN_DATE", SHARETRAN."RESULATION_DATE", SHARETRAN."RESULATION_NO", SHARETRAN."TRAN_TYPE" ,
            SHMASTER."AC_TYPE", SHMASTER."AC_NAME", SCHEMAST."S_NAME", CITYMASTER."CITY_NAME" 
            FROM  SHARETRAN         
            LEFT OUTER JOIN CUSTOMERADDRESS ON CUSTOMERADDRESS."AC_CTCODE" = SHARETRAN."TRAN_NO"
            LEFT OUTER JOIN SHMASTER  ON SHARETRAN."TRAN_ACNO" = SHMASTER."BANKACNO"
            LEFT OUTER JOIN SCHEMAST ON SHARETRAN.ID = SCHEMAST.ID
            LEFT OUTER JOIN CITYMASTER ON SHARETRAN.ID = CITYMASTER."CITY_CODE"
            AND SHMASTER."AC_TYPE" ='.$AC_TYPE.' 
            AND CAST(SHARETRAN."TRAN_DATE" AS DATE) >= ('.$START_DATE.')
            AND CAST(SHARETRAN."TRAN_DATE" AS DATE) <= ('.$END_DATE.')
            Order By SHARETRAN."CERTIFICATE_NO" ';

           // echo $query; 

          
$sql =  pg_query($conn,$query);

$i = 0;
while($row = pg_fetch_assoc($sql))
{ 

     $tmp=[
        'AC_NAME' => $row['AC_NAME'],
        'AC_NO' => $row['AC_NO'],
        'S_NAME' => $row['S_NAME'],
        'CITY_NAME' => $row['CITY_NAME'],
        'TRAN_AMOUNT' => $row['TRAN_AMOUNT'],
        'NO_OF_SHARES' => $row['NO_OF_SHARES'],
        'RESULATION_NO' => $row['RESULATION_NO'],
        'RESULATION_DATE' => $row['RESULATION_DATE'],
        'CERTIFICATE_NO' => $row['CERTIFICATE_NO'],
        'SHARES_FROM_NO' => $row['SHARES_FROM_NO'],
        'SHARES_TO_NO' => $row['SHARES_TO_NO'],
        'SHARES_TRANSFER_DATE' => $row['SHARES_TRANSFER_DATE'],
        'SHARES_RETURN__DATE' => $row['SHARES_RETURN__DATE'],
        // 'NOMINEE_RELATION' => $row['NOMINEE_RELATION'],
        // 'ARTICLE_NAME' => $row['ARTICLE_NAME'],
        // 'TOTAL_WEIGHT_GMS' => $row['TOTAL_WEIGHT_GMS'],
        // 'CLEAR_WEIGHT_GMS' => $row['CLEAR_WEIGHT_GMS'],
        // 'RATE' => $row['RATE'],
        'START_DATE' => $START_DATE,
        'END_DATE' => $END_DATE,
        'BRANCH'  => $BRANCH,
        'AC_TYPE' => $AC_TYPE,
        // 'AC_ACNOTYPE' => $AC_ACNOTYPE,
        
       
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