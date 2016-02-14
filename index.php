<?php 
#die();
require "globals.inc.php";
require "libBlastApp.inc.php";

if (isset($_REQUEST['new']) or !isset($_SESSION['queryData'])){
    $_SESSION['queryData'] = '';
}    
    # $_SESSION['queryData'] could be an array with several positions to 
    # define more aspects of the query e.g. expected value threshold, 
    # number of sequences to print out, etc...
    
// Main form follows
print myheader("home"); ?>
        <h3>Pairwise protein sequence alignment based search with BLAST</h3>
        <p>BlastApp carries out a search with BLASTP and displays the results in a sortable tabular form.</p>
           Check the <a href="http://mmb.pcb.ub.es/formacio/~dbw16/BlastApplication/tutorial.php" target="_blank">Tutorial</a></p>
	<p>Check the code in <a href="https://github.com/durvasa/bioinformatics/tree/master/BlastApplication" target="_blank">GitHub</a></p>
        <form action="search.php"  method="POST" enctype="multipart/form-data">
          <table frame="box" border="0" cellspacing="2" cellpadding="4" align="center">
            <tbody>

	    <tr>
            <td colspan="2"><b>Search parameters</b></td>
            </tr>

            <tr>
            <td align="right"> Databases of choice </td>
            <td><input type="checkbox" name="blast_database[]" value="PDB" width="50"> PDB
            <input type="checkbox" name="blast_database[]" value="SwissProt" width="50"> SwissProt</td> 
            </tr>

            <tr>
            <td align="right"> E-value threshold </td>
            <?php $default_evalue = 0.1 ?>
            <td><input type="text" name="evalue" value="<?php echo $default_evalue ?>" size="5"></td>
            </tr>

            <tr>
            <?php $default_maxnum = 100 ?> 
            <td align="right"> Max number hits </td>
            <td><input type="text" name="maxnum" value="<?php echo $default_maxnum ?>" size="5"></p></td>
            </tr>

            <tr>
             <td valign="top"><b>Query sequence(s)</b></td>          
             <td><em><textarea name="seqQuery" cols="65" rows="10">Provide your protein(s) in FASTA format. Either paste your proteins here or browse a file to upload.</textarea></em></td>
	     </tr>

	     <tr>
	     <td align="right">Upload file</td>
             <td align="left"><input name="seqFile" type="file" value="" type="file" width="50">
	     </td>
             </tr>

	    <tr>
            <td colspan="2" align="right">
            <input type="submit" value="Submit" style="font-face: 'Comic Sans MS'; font-size: larger"> <input type="reset" value="Reset" style="font-face: 'Comic Sans MS'; font-size: larger"></td>
            </tr>
          </tbody>
        </table>
        </form>
        </center>
    </body>
<script type="text/javascript">


$(document).ready(function() {
    $("input:text").focus(function() { $(this).select(); } );
    $("textarea").focus(function() { $(this).select(); } );
});


</script>
</html>
<?php
print myfooter();


