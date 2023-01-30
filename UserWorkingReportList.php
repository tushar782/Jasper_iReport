<?php
ob_start(); 
include "main.php";
require_once('dbconnect.php');

set_time_limit(100);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/UserWorkingReportList.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

$conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");

// variables

 $BRANCH = $_GET['BRANCH'];
 $TRAN_DATE = $_GET['TRAN_DATE'];


 $schm = "'CS'";
 $schm1 = "'TR'";
 $schm2 = "'CL'";
 $schm3 = "'JV'";
 $dateformat ="'DD/MM/YYYY'";

$query = '  SELECT UPPER("USER_CODE")  USER_CODE , 0 OPENED_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm.'
            THEN  1  ELSE 0 END) CASH_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm1.' THEN  1  WHEN '.$schm3.' 
            THEN  1  ELSE 0 END)  TRANSFER_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm2.' THEN  1  ELSE 0 END) CLEARING_AC 
            FROM ACCOTRAN
            WHERE COALESCE("TRAN_ACNO",0) <> 0  AND "TRAN_DATE" = ('.$TRAN_DATE.') 
            GROUP BY (USER_CODE) 
            UNION ALL 
            SELECT UPPER("SYSADD_LOGIN")  SYSADD_LOGIN , CASE "AC_NO" WHEN 0 THEN 0 ELSE 1 END OPENED_AC ,0 CASH_AC , 0 TRANSFER_AC , 0 CLEARING_AC 
            FROM DPMASTER
            WHERE COALESCE("AC_NO",0) <> 0 
            AND ( "AC_OPDATE" IS NULL OR "AC_OPDATE" = ('.$TRAN_DATE.')) 
            Union All 
            SELECT UPPER("USER_CODE")  USER_CODE , 0 OPENED_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm.' 
            THEN  1  ELSE 0 END) CASH_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm1.' THEN  1  WHEN '.$schm3.' 
            THEN  1  ELSE 0 END)  TRANSFER_AC  ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm2.' THEN  1  ELSE 0 END) CLEARING_AC 
            FROM DEPOTRAN WHERE COALESCE(CAST("TRAN_ACNO" AS INTEGER),CAST(0 AS INTEGER)) <> 0  
            AND "TRAN_DATE" =('.$TRAN_DATE.') 
            GROUP BY (USER_CODE) 
            UNION ALL  
            SELECT UPPER("SYSADD_LOGIN")  SYSADD_LOGIN , CASE "AC_NO" WHEN 0 THEN 0 ELSE 1 END OPENED_AC ,0 CASH_AC , 0 TRANSFER_AC , 0 CLEARING_AC 
            FROM LNMASTER
             WHERE COALESCE("AC_NO",0) <> 0 
            AND ( "AC_OPDATE" IS NULL OR "AC_OPDATE" = ('.$TRAN_DATE.')) 
            Union All 
            SELECT UPPER("USER_CODE")  USER_CODE , 0 OPENED_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm.' 
            THEN  1  ELSE 0 END) CASH_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm1.' THEN  1  WHEN '.$schm3.' 
            THEN  1  ELSE 0 END)  TRANSFER_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm2.' THEN  1  ELSE 0 END) CLEARING_AC 
            FROM LOANTRAN 
            WHERE COALESCE(CAST("TRAN_ACNO" AS INTEGER),CAST(0 AS INTEGER)) <> 0  AND "TRAN_DATE" = ('.$TRAN_DATE.') 
            GROUP BY (USER_CODE) 
            UNION ALL  
            SELECT UPPER("SYSADD_LOGIN") SYSADD_LOGIN , CASE "AC_NO" WHEN 0 THEN 0 ELSE 1 END OPENED_AC ,0 CASH_AC , 0 TRANSFER_AC , 0 CLEARING_AC 
            FROM PGMASTER WHERE COALESCE("AC_NO",0) <> 0 
            AND ( "AC_OPDATE" IS NULL OR "AC_OPDATE" = ('.$TRAN_DATE.')) 
            Union All 
            SELECT UPPER("USER_CODE")  USER_CODE , 0 OPENED_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm.' 
            THEN  1  ELSE 0 END) CASH_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm1.' THEN  1  WHEN '.$schm3.' 
            THEN  1  ELSE 0 END)  TRANSFER_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm2.' THEN  1  ELSE 0 END) CLEARING_AC 
            FROM PIGMYTRAN 
            WHERE COALESCE(CAST("TRAN_ACNO" AS INTEGER),CAST(0 AS INTEGER)) <> 0  AND "TRAN_DATE" = ('.$TRAN_DATE.') 
            GROUP BY (USER_CODE) 
            UNION ALL  
            SELECT UPPER("SYSADD_LOGIN")  SYSADD_LOGIN , CASE "AC_NO" WHEN 0 THEN 0 ELSE 1 END OPENED_AC ,0 CASH_AC , 0 TRANSFER_AC , 0 CLEARING_AC 
            FROM SHMASTER
            WHERE COALESCE("AC_NO",0) <> 0 
            AND ( "AC_OPDATE" IS NULL OR "AC_OPDATE" = ('.$TRAN_DATE.')) 
            Union All 
            SELECT UPPER("USER_CODE")  USER_CODE , 0 OPENED_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm.' 
            THEN  1  ELSE 0 END) CASH_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm1.' THEN  1  WHEN '.$schm3.' 
            THEN  1  ELSE 0 END)  TRANSFER_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm2.' THEN  1  ELSE 0 END) CLEARING_AC 
            FROM SHARETRAN 
            WHERE COALESCE(CAST("TRAN_ACNO" AS INTEGER),CAST(0 AS INTEGER)) <> 0  AND "TRAN_DATE" = ('.$TRAN_DATE.') 
            GROUP BY (USER_CODE) 
            Union All 
            SELECT UPPER("USER_CODE")  USER_CODE, 0 OPENED_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm.' 
            THEN  1  ELSE 0 END) CASH_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm1.' THEN  1  WHEN '.$schm3.' 
            THEN  1  ELSE 0 END)  TRANSFER_AC ,SUM(CASE "TRAN_TYPE"  WHEN '.$schm2.' THEN  1  ELSE 0 END) CLEARING_AC 
            FROM DAILYTRAN 
            WHERE CAST("TRAN_STATUS" AS INTEGER) = 1  AND "TRAN_DATE" = ('.$TRAN_DATE.') 
            GROUP BY (USER_CODE) ';

          //echo $query;
          
$sql =  pg_query($conn,$query);

 $i = 0;


while($row = pg_fetch_assoc($sql))
{ 
    

    $tmp=[
        'AC_NO' => 'AC_NO',
        'TRAN_ACNO' => 'TRAN_ACNO',
        'TRAN_TYPE' => 'TRAN_TYPE',
        'schm' => $schm,
        'schm1' => $schm1,
        'schm2' => $schm2,
        'schm3' => $schm3,
        'BRANCH' => $BRANCH,
        'TRAN_DATE' => $TRAN_DATE,
        
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