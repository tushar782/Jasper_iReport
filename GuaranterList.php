<?php

ob_start(); 
include "main.php";
require_once('dbconnect.php');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/GuaranterList1.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

 $conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");


 //variables
$PRINT_DATE = $_GET['PRINT_DATE'];
$BRANCH_CODE = $_GET['BRANCH_CODE'];
$START_DATE = $_GET['START_DATE'];
$END_DATE = $_GET['END_DATE'];
$AC_ACNOTYPE = $_GET['AC_ACNOTYPE'];
$AC_TYPE = $_GET['AC_TYPE'];
$bankName = $_GET['bankName'];
$NAME = $_GET['NAME'];
// $scheme = $_GET['scheme'];      
// $branch = $_GET['branch'];
$dateformate = "'DD/MM/YYYY'";



 $query = 'SELECT LNMASTER."AC_ACNOTYPE",LNMASTER."AC_TYPE",LNMASTER."AC_NO",LNMASTER."AC_NAME",CUSTOMERADDRESS."AC_WARD",CUSTOMERADDRESS."AC_ADDR",
           GUARANTERDETAILS."AC_NAME" "GAC_NAME" ,CITYMASTER."CITY_NAME",SCHEMAST."S_NAME"
           FROM LNMASTER
           INNER JOIN GUARANTERDETAILS ON LNMASTER."idmasterID" = GUARANTERDETAILS."lnmasterID"
           LEFT JOIN SHMASTER ON CAST(GUARANTERDETAILS."MEMBER_TYPE" AS INTEGER) = SHMASTER."AC_TYPE"
           LEFT JOIN SCHEMAST ON  CAST(GUARANTERDETAILS."AC_TYPE" AS INTEGER) = SCHEMAST.ID
           LEFT JOIN CITYMASTER ON CITYMASTER."CITY_CODE" = LNMASTER."AC_TYPE"
           LEFT OUTER JOIN CUSTOMERADDRESS ON LNMASTER.id = CUSTOMERADDRESS."idmasterID"
           AND(LNMASTER."AC_OPDATE" IS NULL OR CAST(LNMASTER."AC_OPDATE" AS DATE) <= DATE('.$START_DATE.')) 
           AND(LNMASTER."AC_CLOSEDT" IS NULL OR CAST(LNMASTER."AC_CLOSEDT" AS DATE) > DATE('.$END_DATE.')) 
           AND LNMASTER."AC_ACNOTYPE" ='.$AC_ACNOTYPE.' 
           AND LNMASTER."AC_TYPE" ='.$AC_TYPE.'
           AND LNMASTER."BRANCH_CODE" = '.$BRANCH_CODE.'
           ORDER BY LNMASTER."AC_ACNOTYPE", LNMASTER."AC_TYPE", LNMASTER."AC_NO"';

        //    echo $query; 

          
$sql =  pg_query($conn,$query);

$i = 0;
while($row = pg_fetch_assoc($sql))
{ 

     $tmp=[
        'AC_NO' => $row['AC_NO'],
        'AC_NAME' => $row['GAC_NAME'],
        'AC_WARD' => $row['AC_WARD'],
        'AC_ADDR' => $row['AC_ADDR'],
        'CITY_NAME'=> $row['CITY_NAME'],
        'GAC_NAME'=> $row['GAC_NAME'],
        'S_NAME' => $row['S_NAME'],
        // 'BRANCH' => $BRANCH,
        'AC_ACNOTYPE' => $AC_ACNOTYPE,
        'AC_TYPE' => $AC_TYPE,
        'bankName'=> $bankName,
        'NAME' => $NAME,
        'BRANCH_CODE' => $BRANCH_CODE,
        'PRINT_DATE' => $PRINT_DATE,
       
     ];
    
    $data[$i]=$tmp;
    $i++;
    
}
ob_end_clean();

$config = ['driver'=>'array','data'=>$data];
// print_r($data);
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');

?> 
    

