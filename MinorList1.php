<?php
include "main.php";
ob_start(); 

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: http://localhost:4200');
//  header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
header('Access-Control-Max-Age: 1000');  
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');

use simitsdk\phpjasperxml\PHPJasperXML;

// $filename = __DIR__.'/ODRegister.jrxml';

$filename = __DIR__.'/MinorList1.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

//connect pgAdmin database connection 
$conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");

// connection message
if($conn)
{
    echo 'Open Database Succesfully';
 
}else
{
    echo 'fail';
}
//variables
$print_date = $_GET['print_date'];
$ac_type = $_GET['ac_type'];
$sdate = $_GET['sdate'];
$edate = $_GET['edate'];
$branch_name = $_GET['branch_name'];
$AC_ACNOTYPE = $_GET['AC_ACNOTYPE'];


// $startDate = "'01/01/2025'";
// $startDate = $_GET['startDate'];
// $scheme = $_GET['scheme'];
$dateformate = "'DD/MM/YYYY'";


//get data from table

$query =' SELECT DPMASTER."AC_ACNOTYPE", DPMASTER."AC_TYPE", DPMASTER."AC_NO", DPMASTER."AC_NAME", DPMASTER."AC_MBDATE", DPMASTER."AC_GRDNAME",DPMASTER."AC_GRDRELE", DPMASTER."AC_GRDNAME",DPMASTER."AC_GRDRELE" ,SCHEMAST."S_NAME" 
          FROM DPMASTER  
          LEFT OUTER JOIN SCHEMAST 
          ON DPMASTER."AC_ACNOTYPE" = SCHEMAST."S_ACNOTYPE" 
          AND DPMASTER."AC_TYPE" = SCHEMAST."S_APPL"
          WHERE CAST(DPMASTER."AC_MINOR" AS integer) <> 0 
          AND ( DPMASTER."AC_OPDATE" IS NULL OR CAST(DPMASTER."AC_OPDATE" AS date) <= DATE('.$sdate.' )) 
          OR (DPMASTER."AC_CLOSEDT" IS NULL OR CAST(DPMASTER."AC_CLOSEDT" AS date) > DATE('.$edate.' ))
          AND DPMASTER."AC_ACNOTYPE" = '.$AC_ACNOTYPE.'
          AND DPMASTER."AC_TYPE"     =  '.$ac_type.'
          ORDER BY DPMASTER."AC_ACNOTYPE", DPMASTER."AC_TYPE", DPMASTER."AC_NO" ';

$sql =  pg_query($conn,$query);

$i = 0;
 
while($row = pg_fetch_assoc($sql)){

    $tmp=[

        'AC_NO'=> $row['AC_NO'],
        'AC_NAME'=> $row['AC_NAME'],
        'AC_ACNOTYPE' =>$scheme,
        'AC_MBDATE' => $row['AC_MBDATE'],
        'AC_GRDNAME' => $row['AC_GRDNAME'],     
        'AC_GRDRELE' => $row['AC_GRDRELE'],  
        'age' =>$row['extract'],
        'USER_DATE' =>$startDate,
        'scheme' => $scheme,
        'sdate'=> $sdate,
        'edate'=> $edate,
        // 'schemecode'=> 'TD',
        'print_date'=> $print_date,
        'branch_name' => $branch_name,
        
    ];    
    $data[$i]=$tmp;
    $i++;   

 
}

// for clean previous execution
ob_end_clean();
// $pdf->Output($file, 'I');
$config = ['driver'=>'array','data'=>$data];
// for pdf conversion of report
// print_r($data);
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');
    
    $PHPJasperXML->arrayParameter=array("parameter1"=>$value);
