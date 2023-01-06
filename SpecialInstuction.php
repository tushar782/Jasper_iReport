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
 

$sdate_ = $_GET['sdate_'];
$edate_ = $_GET['edate_'];
$revoke = $_GET['revoke'];
$bankName = $_GET['bankName'];

// echo $sdate;
// echo $edate;
// echo $revoke;
// echo $bankName;


// $bankName = str_replace("'", "", $bankName);
// $sdate_ = str_replace("'", "", $sdate_);
// $edate_ = str_replace("'", "", $edate_);
// $branchName = str_replace("'", "", $branchName);

// $query = 'SELECT specialinstruction."INSTRUCTION_DATE", specialinstruction."INSTRUCTION_NO", 
//           specialinstruction."TRAN_ACNO",specialinstruction."DETAILS", specialinstruction."FROM_DATE", 
//           specialinstruction."TO_DATE",specialinstruction."SYSADD_LOGIN", specialinstruction."SYSCHNG_LOGIN",
//           ownbranchmaster."id",ownbranchmaster."NAME"
//           FROM specialinstruction, ownbranchmaster
//           where cast("INSTRUCTION_DATE" as date) 
//            between to_date('.$stdate.','.$dateformate.') and to_date('.$etdate.','.$dateformate.')
//            AND ownbranchmaster."id" = '.$branch.'  ';

$query = ' select "REVOKE_DATE" from specialinstruction 
           where cast(SPECIALINSTRUCTION."INSTRUCTION_DATE" as date) >= cast('.$sdate_.' as date)
           And cast(SPECIALINSTRUCTION."INSTRUCTION_DATE" as date) <= cast('.$edate_.' as date)';

           echo $query;
          
$sql =  pg_query($conn,$query);



$i = 0;

if (pg_num_rows($sql) == 0)
{
    include "errormsg.html";
}
else
{
while($row = pg_fetch_assoc($sql))
{
   // echo $sql;

    $tmp=[
        'TRAN_ACNO' => $row['TRAN_ACNO'],
        'ACCOUNT_NAME'=>$row['ACCOUNT_NAME'],
        'DETAILS' => $row['DETAILS'],
        // 'INSTRUCTION_DATE' => $row['INSTRUCTION_DATE'],
        // 'INSTRUCTION_NO'=> $row['INSTRUCTION_NO'],
        'NAME' => $row['NAME'],
        'FROM_DATE'=> $row['FROM_DATE'],
        'TO_DATE'=> $row['TO_DATE'],
        'SYSADD_'=> $row['SYSADD_'],
        'SYSCHING_LOGIN'=> $row['SYSCHING_LOGIN'],

        'branch'=> 'MIDC',
        'revoke'=> '$revoke',
        'bankName'=> 'XYZ',
       
    ];
    $data[$i]=$tmp;
    $i++;
    
}
// ob_end_clean();

$config = ['driver'=>'array','data'=>$data];
//print_r($data);
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');
    
}   
?>
