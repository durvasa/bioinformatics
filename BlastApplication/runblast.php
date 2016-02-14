<?php 

require "globals.inc.php";
require "libBlastApp.inc.php";

// Take data from $_SESSION, loaded in search.php, if empty back to front page
if (!isset($_SESSION['queryData']) or !$_SESSION['queryData']){
    header('Location:index.php');
}
// prepare FASTA file
// Identify file format, ">" as first char indicates FASTA
$p = strpos($_SESSION['queryData'], '>');
# strpos returns False if not found and "0" if first character in the string
# When is not already FASTA, add header + new line
if (!$p and ( $p !== 0)) { 
    $_SESSION['queryData'] = ">User-provided sequence\n".$_SESSION['queryData'];
}

// Set temporary file name to a unique value to protect from concurrent runs
#$tmp_file = $tmp_dir . "/test";
$_SESSION['uniqueId'] = uniqId('ID');
$tmp_file = $tmp_dir . "/" . $_SESSION['uniqueId'];
$tmp_input = $tmp_file . ".query.fasta";
$tmp_output = $tmp_file . ".blast.out";

// Open temporary file and store query FASTA
$ff = fopen("$tmp_input", 'wt');
fwrite($ff, $_SESSION['queryData']);
fclose($ff);

// Load the options for the blast database
foreach (array_values($_SESSION['dataBase']) as $dbindex){
	$blast_search_db[] = $blast_db_dir . "/" . $blast_db[$dbindex];
	$blast_search_db_names[] = $blast_db[$dbindex];
}

$db_query_text = join(' ', $blast_search_db);
$db_query_text_names = join(' ', $blast_search_db_names);

// Load the options for the E-value threshold
$evalue = $_SESSION['eValue'];

// Load the options for the count limit
$count_limit= $_SESSION['countLimit'];

// Execute standalone blast and keep a command line log

$blast_cmd_line = $blast_exe . " -d " . "\"" . $db_query_text . "\"" . " -p blastp -e " . $evalue . " -v " . $count_limit . " -b 0";
$command_line = $blast_cmd_line . " -i " . $tmp_input . " -o " . $tmp_output; 
exec($command_line);

$command_line_log = "blastall -d \"" . $db_query_text_names . "\"" ." -p blastp -e " . $evalue . " -v " . $count_limit . " -b 0" . " -i " . "query.fasta" . " -o " . "blast.out";

// Read results file and parse hits onto $result[]
// file() produces an array each position of which contains a line
$blast = file($tmp_output);

$i = 0;
$total_hits = 0;
$first_reference = True;
$results_boolean = False;
$hits_boolean = False;
foreach (array_values($blast) as $bb){
	$i++;
	if ($i == 1){
		$blast_version = rtrim($bb,"\n");
	}
	if (preg_match('/Reference/', $bb) and $first_reference){
        	$references_boolean = True;
	}
	if ($references_boolean == True and !preg_match('/Query/',$bb)){
		$references[] = $bb;
	}
	if (preg_match('/Query\s?=\s?(.*)\n/', $bb, $match)){
		$references_boolean = False;
		$first_reference = False;
		list ($r , $query_id) = $match; 
	}
	if (preg_match('/Sequences producing/', $bb)){
		$results_boolean = True;
	}
	if (preg_match("/(....)_(.) mol:([^ ]*) length:([0-9]+) (.*)/", $bb) or preg_match("/(sp\|)(......)\|(.*)/", $bb)){
		$hits_boolean = True;
		$result[$query_id][] = $bb;
		$total_hits++;
        }
	if ($results_boolean == True and (preg_match('/BLASTP/', $bb) or preg_match('/Database/', $bb))){
		$results_boolean = False;
	}
}


if ($hits_boolean == False){
        errorPage("error", "Sorry, the BLAST search produced no results. Make sure that your input is in a valid FASTA format.");
}
else {
    print myheader("results browser");
    ?>
    
    <p class="button"><a href="index.php?new=1">New Search</a></p>
    <p class="button"><a href="download.php">Download Results</a><p>
 
<table border="0" cellspacing="2" cellpadding="4" align="center">
<col width="200"><col width="750">
     <tbody>
            <tr>
            <td><b>Version:</b></td>
	    <td><p><?php print ($blast_version) ?></p></td>
            </tr>

            <tr>
            <td valign="top"><b>References:</b></td>
            <td>
                 <?php
                 foreach (array_values($references) as $ref){
                       print $ref;
                 }
                 ?>   </td>
            </tr>

            <tr>
            <td valign="top"><b>Query sequences:</b></td>
            <td>
		<?php
		      foreach ($result as $key => $ss){ 
		      	$query_keys[] = $key;
		      	$printable = join('; ',$query_keys);
		      }?>
		      <p><?php print $printable . "."?></p>
            </td>
            </tr>

            <tr>
            <td><b>Command-line log:</b></td>
            <td><?php print $command_line_log?></td>
            </tr>

            <tr>
             <td valign="top"><b>Databases consulted:</b></td>
             <td> <?php print join(" and ", $_SESSION['dataBase'])?></td>
            </tr>

            <tr>
             <td><b>Total number of hits:</b></td>
	     <td><?php print $total_hits ?> </td>
            </tr>
          </tbody>
        </table>


    <table border="1" cellspacing="1" cellpadding="1" width="100%" id="blastTable">
        <thead>
            <tr>
                <th>Protein Id</th>
		<th>Query Seq</th>
                <th>Protein Description</th>
                <th>Score</th>
                <th>E-value</th>
            </tr>
        </thead>
        
        <tbody>

        <?php
        // parsing hit following specific format, note that this format is not standard. It comes from the 
        // headers used to generate BLAST databases, this is from PDB
        foreach ($result as $key => $ss){
	   $query_id = $key;
	   foreach (array_values($ss) as $rr){			 
           if (strlen(rtrim($rr,'\n')) > 1) {
                $rr2 = $rr;
                preg_match("/(....)_(.) mol:([^ ]*) length:([0-9]+) (.*)/", $rr, $hits);
              if ($hits){
                $entry_type = 'pdb';
                list ($r, $idCode, $sub, $type, $l, $rest) = $hits;
                $rest_array = preg_split( '/\s+/', $rest, -1, PREG_SPLIT_NO_EMPTY);
                $e_value = array_pop($rest_array);
                $score = array_pop($rest_array);
                $descr = join(' ', $rest_array);
              }
              else {
                $entry_type ='sprot';
                $rr2_array = preg_split( '/\s+/', $rr2, -1, PREG_SPLIT_NO_EMPTY);
                $e_value = array_pop($rr2_array);
                $score = array_pop($rr2_array);
                $rr3 = join(' ', $rr2_array);
		preg_match("/(sp\|)(......)\|(.*)/", $rr3, $new_hits);
                list ($r, $tail, $idCode, $rest_array) = $new_hits;
                $descr = $rest_array;
              }
}?>
   
            <tr>

        <?php 
              if ($entry_type == 'pdb'){ ?>      
                <td><a href="http://www.rcsb.org/pdb/explore/explore.do?structureId=<?php print $idCode ?>" target="_blank"><?php print $idCode . "_$sub" ?></a></td> <?php }
              else { ?>
                <td><a href="http://www.uniprot.org/uniprot/<?php print $idCode ?>" target="_blank"><?php print "sp|" . $idCode ?></a></td>
        <?php }     ?>
                <td><?php print $query_id?></td>
                <td><?php print $descr ?></td>
                <td><?php print $score ?></td>
                <td><?php print trans_numeric($e_value) ?></td>
            </tr>
        <?php
            }
        }
    }
?>
        
        </tbody>
    </table>

<?php
// Cleaning temporary files
    if (file_exists($tmp_input)){
        unlink($tmp_input);
    }
//    if (file_exists($tmp_output)){
//        unlink($tmp_output);
//  }
print myfooter();
?>

<script type="text/javascript">
$(document).ready(function(){
	$('#blastTable').DataTable({
		/* "order": [[6, "asc"]] */
		   "paging": true
		/* "colReorder": true */
	});
});
</script>
