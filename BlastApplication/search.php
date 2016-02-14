<?php 

include "globals.inc.php";
include "libBlastApp.inc.php";


$_SESSION['queryData'] = $_REQUEST['seqQuery'];
$_SESSION['dataBase'] = $_REQUEST['blast_database'];

if(!$_REQUEST['maxnum']){
// Default max number of hits
	$_SESSION['countLimit'] = 100;
}
else{
	$_SESSION['countLimit'] = $_REQUEST['maxnum'];
}

if(!$_REQUEST['evalue']){
// Default E-value threshold
	$_SESSION['eValue'] = 0.001;
}
else{
	$_SESSION['eValue'] = $_REQUEST['evalue'];
}


if (!$_SESSION['dataBase']){
errorPage("invalid database query", "You must pick at least one database");
}

if ($_FILES['seqFile']['name'] or $_REQUEST['seqQuery']) {
    if ($_FILES['seqFile']['tmp_name']) {
        $_SESSION['queryData'] = file_get_contents($_FILES['seqFile']['tmp_name']);
    }
    header('Location: runblast.php');
}

else{
    errorPage("invalid sequence query", "You must submit at least one FASTA sequence or file");
}


