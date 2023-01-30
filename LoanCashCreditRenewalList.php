<?php
ob_start(); 
include "main.php";
//require_once('dbconnect.php');

set_time_limit(100);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/LoanCashCreditRenewalList.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

$conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");

// variables
//  $print_date = $_GET['print_date'];
 $AC_TYPE = $_GET['AC_TYPE'];
 $START_DATE = $_GET['START_DATE'];
 $END_DATE = $_GET['END_DATE'];
 $AC_ACNOTYPE = $_GET['AC_ACNOTYPE'];
 $BRANCH = $_GET['BRANCH'];

// $schemecode = "'TD'";
$dateformat ="'DD/MM/YYYY'";
// $ac_type ="'15'";

$query = '(SELECT RENEWALHISTORY."RENEWAL_DATE", RENEWALHISTORY."AC_RENEWAL_COUNTER", RENEWALHISTORY."AC_ACNOTYPE", RENEWALHISTORY."AC_TYPE",RENEWALHISTORY."OLD_DRAWING_POWER",
RENEWALHISTORY."AC_NO" , RENEWALHISTORY."NEW_EXPIRY_DATE" , SCHEMAST."S_NAME",LNMASTER."AC_NAME" , GUARANTERDETAILS."AC_ACNOTYPE" G_ACNOTYPE,RENEWALHISTORY."NEW_INTEREST_RATE",
GUARANTERDETAILS."AC_TYPE" G_ACTYPE, GUARANTERDETAILS."AC_NO" G_ACNO, GUARANTERDETAILS."AC_NAME" GUARENTER_NAME , LNMASTER."AC_SANCTION_AMOUNT",RENEWALHISTORY."NEW_MATURITY_AMOUNT",
LNMASTER."AC_RESO_NO", LNMASTER."AC_RESO_DATE", LNMASTER."AC_DRAWPOWER_AMT" , LNMASTER."AC_EXPIRE_DATE",GUARANTERDETAILS."MEMBER_NO",
GETLNINTRATE(CAST(SCHEMAST."S_APPL" AS CHARACTER VARYING) , CAST(LNMASTER."BANKACNO" AS CHARACTER VARYING) , CAST(RENEWALHISTORY."RENEWAL_DATE" AS CHARACTER VARYING)) LNINTRATE
FROM  RENEWALHISTORY   
LEFT OUTER JOIN SCHEMAST ON RENEWALHISTORY."AC_TYPE"  = SCHEMAST.ID 
LEFT OUTER JOIN LNMASTER ON RENEWALHISTORY."AC_TYPE" = LNMASTER."AC_TYPE"
INNER JOIN  GUARANTERDETAILS ON  CAST(LNMASTER."BANKACNO" AS BIGINT) = CAST(GUARANTERDETAILS."AC_NO" AS BIGINT)
AND RENEWALHISTORY."AC_ACNOTYPE" = SCHEMAST."S_ACNOTYPE"       
AND ( CAST(LNMASTER."AC_OPDATE" AS DATE) IS NULL OR CAST(LNMASTER."AC_OPDATE" AS DATE) <= CAST('.$END_DATE.'AS DATE))
AND (CAST(LNMASTER."AC_CLOSEDT" AS DATE) IS NULL OR CAST(LNMASTER."AC_CLOSEDT" AS DATE) > CAST('.$END_DATE.' AS DATE)) 
AND RENEWALHISTORY."AC_ACNOTYPE" = '.$AC_ACNOTYPE.'
 AND RENEWALHISTORY."AC_TYPE" ='.$AC_TYPE.' 
AND CAST(RENEWALHISTORY."RENEWAL_DATE" AS DATE) >= CAST('.$START_DATE.' AS DATE) 
AND CAST(RENEWALHISTORY."RENEWAL_DATE" AS DATE) <= CAST('.$END_DATE.' AS DATE))'; 

         // echo $query;
          
$sql =  pg_query($conn,$query);

 $i = 0;


while($row = pg_fetch_assoc($sql))
{ 
    

    $tmp=[
        'NEW_INTEREST_RATE' => $row['NEW_INTEREST_RATE'],
        'AC-SANCTION_AMOUNT' => $row['AC_SANCTION_AMOUNT'],
        'AC_RENEWAL_COUNTER' => $row['AC_RENEWAL_COUNTER'],
        'S_NAME' => $row['S_NAME'],
        'AC_RESO_NO' => $row['AC_RESO_NO'],
        'NEW_EXPIRY_DATE' => $row['NEW_EXPIRY_DATE'],
        'RECEPIT_NO'=> $row['RECEPIT_NO'],
        'AC_NAME'=> $row['AC_NAME'],
        'OLD_DRAWING_POWER' => $row['OLD_DRAWING_POWER'],
        'AC_DRAWPOWER_AMT' => $row['AC_DRAWPOWER_AMT'],
        'NEW_MATURITY_AMOUNT' => $row['NEW_MATURITY_AMOUNT'],
        'S_APPL' => $row['S_APPL'],
        'AC_NO'=> $row['AC_NO'],
        'AC_TYPE' => '$AC_TYPE',
        'AC_ACNOTYPE' => $AC_ACNOTYPE,
        'START_DATE' => $START_DATE,
        'END_DATE' => $END_DATE ,
        'BRANCH' => $BRANCH,
        // 'print_date'=>  $print_date,
    ];
    $data[$i]=$tmp;
    $i++;  
}
ob_end_clean();
// echo $query;

$config = ['driver'=>'array','data'=>$data];
// echo $filename;
 //print_r($data)
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');
    
?>