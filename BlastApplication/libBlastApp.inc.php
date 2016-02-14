<?php

function myheader($title) {
    return "
<html>
    <head>
        <title>$title</title>
        <meta charset='UTF-8'>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"estil.css\">
        <link rel=\"stylesheet\" href=\"http://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css\"/>
        <link rel=\"stylesheet\" href=\"https://cdn.datatables.net/colreorder/1.3.0/css/colReorder.dataTables.min.css\"/>
	<script type=\"text/javascript\" src=\"https://code.jquery.com/jquery-2.2.0.min.js\"></script>
        <script type=\"text/javascript\" src=\"https://code.jquery.com/jquery-1.12.0.min.js\"></script>
	<script type=\"text/javascript\" src=\"http://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js\"></script>
	<script type=\"text/javascript\" src=\"https://cdn.datatables.net/colreorder/1.3.0/js/dataTables.colReorder.min.js\"></script>
    </head>
    <body bgcolor=\"#ffffff\">
        <h1>BlastApp - $title</h1>";
}

function myfooter() {
    return '
</body>
<footer>
<p><em>Developed by Ferran Mui&ntildeos: <a href="http://mmb.pcb.ub.es/formacio/~dbw16/">http://mmb.pcb.ub.es/formacio/~dbw16/</a></em></p>
</footer>
</html>';
}

function errorPage($title, $text) {
    die(myheader($title) . $text . myfooter());
}


function trans_numeric($evalue_string) {
        if ($evalue_string == "0.0"){
                return 0;
        }
        elseif (preg_match('/(.*)e-([0-9]+)/', $evalue_string, $hits)){
                list ($r, $coef, $exponent) = $hits;
                if ($coef == ''){
			$coef = 1;
		}
		else{
			$coef = (int)$coef;
		}
		
		return $coef*pow(10,-$exponent);
        }
        else {
                return (float)$evalue_string;
        }
}?>


