<?php
ob_start(); 
include "main.php";
require_once('dbconnect.php');

set_time_limit(100);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/HighRecPay.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

$conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");

// variables
$START_DATE = $_GET['START_DATE'];
$END_DATE = $_GET['END_DATE'];
$BRANCH = $_GET['BRANCH'];
$AC_TYPE = $_GET['AC_TYPE'];



$schemecode = "'SB'";
$ac_type ="'8'";
$dateformat ="'DD/MM/YYYY'";





    $query = 'SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("TRAN_AMOUNT" AS FLOAT) TRAN_AMOUNT 
              FROM DEPOTRAN
              WHERE  CAST("TRAN_AMOUNT" AS FLOAT) >= 25000 And CAST("TRAN_AMOUNT" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE ) >= ('.$END_DATE.') 
              AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.')
              UNION ALL 							
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("INTEREST_AMOUNT" AS FLOAT) TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  CAST("INTEREST_AMOUNT" AS INTEGER) >= 25000 And CAST("INTEREST_AMOUNT" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("PENAL_INTEREST" AS FLOAT) TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  CAST("PENAL_INTEREST" AS FLOAT) >= 25000 And CAST("PENAL_INTEREST" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "RECPAY_INT_AMOUNT" TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "RECPAY_INT_AMOUNT" >= 25000 And "RECPAY_INT_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER1_AMOUNT" TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "OTHER1_AMOUNT" >= 25000 And "OTHER1_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER2_AMOUNT" TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "OTHER2_AMOUNT" >= 25000 And "OTHER2_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER3_AMOUNT" TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "OTHER3_AMOUNT" >= 25000 And "OTHER3_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER4_AMOUNT" TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "OTHER4_AMOUNT" >= 25000 And "OTHER4_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER5_AMOUNT" TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "OTHER5_AMOUNT" >= 25000 And "OTHER5_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER6_AMOUNT" TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "OTHER6_AMOUNT" >= 25000 And "OTHER6_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER7_AMOUNT" TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "OTHER7_AMOUNT" >= 25000 And "OTHER7_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER8_AMOUNT" TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "OTHER8_AMOUNT" >= 25000 And "OTHER8_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER9_AMOUNT" TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "OTHER9_AMOUNT" >= 25000 And "OTHER9_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER10_AMOUNT" TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "OTHER10_AMOUNT" >= 25000 And "OTHER10_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("ADDED_PENAL_INTEREST" AS FLOAT) TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  CAST("ADDED_PENAL_INTEREST" AS FLOAT) >= 25000 And CAST("ADDED_PENAL_INTEREST" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("REC_PENAL_INT_AMOUNT" AS FLOAT) TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  CAST("REC_PENAL_INT_AMOUNT" AS FLOAT) >= 25000 And CAST("REC_PENAL_INT_AMOUNT" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("OTHER11_AMOUNT" AS FLOAT) TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  "OTHER11_AMOUNT" >= 25000 And "OTHER11_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("TRAN_AMOUNT" AS FLOAT)  TRAN_AMOUNT 
              FROM DEPOTRAN WHERE  CAST("TRAN_AMOUNT" AS FLOAT) >= 25000 And CAST("TRAN_AMOUNT" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("TRAN_AMOUNT" AS FLOAT) TRAN_AMOUNT 
              FROM LOANTRAN WHERE  CAST("TRAN_AMOUNT" AS FLOAT) >= 25000 And CAST("TRAN_AMOUNT" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("PENAL_INTEREST" AS FLOAT) TRAN_AMOUNT 
              FROM LOANTRAN WHERE  CAST("PENAL_INTEREST" AS FLOAT) >= 25000 And CAST("PENAL_INTEREST" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("RECPAY_INT_AMOUNT" AS FLOAT) TRAN_AMOUNT 
              FROM LOANTRAN WHERE  CAST("RECPAY_INT_AMOUNT" AS FLOAT) >= 25000 And CAST("RECPAY_INT_AMOUNT" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("INTEREST_GLACNO" AS FLOAT) TRAN_AMOUNT 
              FROM LOANTRAN WHERE  CAST("INTEREST_GLACNO" AS FLOAT) >= 25000 And CAST("INTEREST_GLACNO" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("INTEREST_AMOUNT" AS FLOAT) TRAN_AMOUNT 
              FROM LOANTRAN WHERE  CAST("INTEREST_AMOUNT" AS FLOAT) >= 25000 And CAST("INTEREST_AMOUNT" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER1_AMOUNT" TRAN_AMOUNT 
              FROM LOANTRAN WHERE  "OTHER1_AMOUNT" >= 25000 And "OTHER1_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER2_AMOUNT" TRAN_AMOUNT 
              FROM LOANTRAN WHERE  "OTHER2_AMOUNT" >= 25000 And "OTHER2_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER3_AMOUNT" TRAN_AMOUNT 
              FROM LOANTRAN WHERE  "OTHER3_AMOUNT" >= 25000 And "OTHER3_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER4_AMOUNT" TRAN_AMOUNT 
              FROM LOANTRAN WHERE  "OTHER4_AMOUNT" >= 25000 And "OTHER4_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <=('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER5_AMOUNT" TRAN_AMOUNT 
              FROM LOANTRAN WHERE  "OTHER5_AMOUNT" >= 25000 And "OTHER5_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER6_AMOUNT" TRAN_AMOUNT 
              FROM LOANTRAN WHERE  "OTHER6_AMOUNT" >= 25000 And "OTHER6_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER7_AMOUNT" TRAN_AMOUNT 
              FROM LOANTRAN WHERE  "OTHER7_AMOUNT" >= 25000 And "OTHER7_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER8_AMOUNT" TRAN_AMOUNT 
              FROM LOANTRAN WHERE  "OTHER8_AMOUNT" >= 25000 And "OTHER8_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER9_AMOUNT" TRAN_AMOUNT 
              FROM LOANTRAN WHERE  "OTHER9_AMOUNT" >= 25000 And "OTHER9_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER10_AMOUNT" TRAN_AMOUNT 
              FROM LOANTRAN WHERE  "OTHER10_AMOUNT" >= 25000 And "OTHER10_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("ADDED_PENAL_INTEREST" AS FLOAT) TRAN_AMOUNT 
              FROM LOANTRAN WHERE  CAST("ADDED_PENAL_INTEREST" AS FLOAT) >= 25000 And CAST("ADDED_PENAL_INTEREST" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("REC_PENAL_INT_AMOUNT" AS FLOAT) TRAN_AMOUNT 
              FROM LOANTRAN WHERE  CAST("REC_PENAL_INT_AMOUNT" AS FLOAT) >= 25000 And CAST("REC_PENAL_INT_AMOUNT" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE"  , "TRAN_DRCR" , "OTHER11_AMOUNT" TRAN_AMOUNT 
              FROM LOANTRAN WHERE  "OTHER11_AMOUNT" >= 25000 And "OTHER11_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("TRAN_AMOUNT" AS FLOAT) TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  CAST("TRAN_AMOUNT" AS FLOAT) >= 25000 And CAST("TRAN_AMOUNT" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("INTEREST_AMOUNT" AS FLOAT) TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  CAST("INTEREST_AMOUNT" AS FLOAT) >= 25000 And CAST("INTEREST_AMOUNT" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , CAST("RECPAY_INT_AMOUNT" AS FLOAT) TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  CAST("RECPAY_INT_AMOUNT" AS FLOAT) >= 25000 And CAST("RECPAY_INT_AMOUNT" AS FLOAT) <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER1_AMOUNT" TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  "OTHER1_AMOUNT" >= 25000 And "OTHER1_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER2_AMOUNT" TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  "OTHER2_AMOUNT" >= 25000 And "OTHER2_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER3_AMOUNT" TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  "OTHER3_AMOUNT" >= 25000 And "OTHER3_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER4_AMOUNT" TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  "OTHER4_AMOUNT" >= 25000 And "OTHER4_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER5_AMOUNT" TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  "OTHER5_AMOUNT" >= 25000 And "OTHER5_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER6_AMOUNT" TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  "OTHER6_AMOUNT" >= 25000 And "OTHER6_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER7_AMOUNT" TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  "OTHER7_AMOUNT" >= 25000 And "OTHER7_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER8_AMOUNT" TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  "OTHER8_AMOUNT" >= 25000 And "OTHER8_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER9_AMOUNT" TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  "OTHER9_AMOUNT" >= 25000 And "OTHER9_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER10_AMOUNT" TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  "OTHER10_AMOUNT" >= 25000 And "OTHER10_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE" , "TRAN_DRCR" , "OTHER11_AMOUNT" TRAN_AMOUNT 
              FROM PIGMYTRAN WHERE  "OTHER11_AMOUNT" >= 25000 And "OTHER11_AMOUNT" <= 9999999.99 AND CAST("TRAN_DATE" AS DATE) >= ('.$END_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.') 
              UNION ALL 
              SELECT "TRAN_ACNOTYPE" , "TRAN_ACTYPE" , "TRAN_ACNO" , "TRAN_DATE"  , "TRAN_DRCR" , "TRAN_AMOUNT" 
              FROM VWDETAILDAILYTRAN WHERE CAST("TRAN_STATUS" AS INTEGER) = '.$AC_TYPE.' 
              AND CAST("TRAN_AMOUNT" AS FLOAT) >= 25000 AND CAST("TRAN_AMOUNT" AS FLOAT) <= 9999999.99 
              AND CAST("TRAN_DATE" AS DATE) >= ('.$START_DATE.') AND CAST("TRAN_DATE" AS DATE) <= ('.$END_DATE.')'; 
             

                 // echo $query;

             
$sql =  pg_query($conn,$query);

 $i = 0;

while($row = pg_fetch_assoc($sql))
{ 
    
    $tmp=[
    
        
        'START_DATE' => $START_DATE,
        'END_DATE'  => $END_DATE,
        'BRANCH'  => $BRANCH,
        'AC_TYPE' => $AC_TYPE,
    ];
    $data[$i]=$tmp;
    $i++;  
}
ob_end_clean();
// echo $query;

$config = ['driver'=>'array','data'=>$data];
// echo $filename;
//print_r($data);
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');
    
?>