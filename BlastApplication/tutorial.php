<?php 

require "globals.inc.php";
require "libBlastApp.inc.php";
    
// Page follows
print myheader("tutorial"); ?>

<p class="button"><a href="index.php?new=1">New Search</a></p>

<h4>Sequence queries:</h4>
<p>Queries can be submitted in the text area and also as a file. If the 
data is submitted using both means, BlastApp will only consider the data
enclosed in the file. Several entries can be submitted simultaneously. 
</p>

<h4>Databases:</h4>
<p>BlastApp can run searches on either PDB or SwissProt --  or both. 
The search results provide the respective standard protein codes 
linked to the main reference entries at Protein Data Bank or UniprotKB.</p>
<p> BlastApp can render a BLASTP search in both databases 
simultaneously. This option is selected by ticking the two database 
checkboxes. While doing so, the E-values are automatically adjusted 
to reflect the size of the virtual joint database.</p>

<h4>Options:</h4>
<p>BlastApp requires the user to specify the maximum E-value threshold 
and the maximum number of hits for each query protein submitted. Valid 
E-value thresholds can be submitted in exponential notation e.g. 7E-15 
or in decimal number notation e.g. 0.032.</p>
<p>Would either of these options not be defined, BlastApp will take as
default E-value threshold = 0.001 and max. number of hits = 100.
</p>

<h4>Results:</h4>
<p>The results are displayed in tabular format with hits in rows and 
attributes in columns: standard protein hit identifier | 
query sequence identifier | protein description | score attained by 
pairwise sequence alignment between the sequence query and the protein hit 
| associated E-value.</p>
<p>Click on the table's headers to sort the output by the column's value. 
The table's format allows the entries to be sorted by the numerical values of 
score and E-value.</p>
<p>Use the right hand "search" bar to browse the 
results by specific column content. Use the "show" display to change the 
number of hits (rows) displayed per table page. Use the bottom-right "paging" 
buttons to navigate the tables.</p>

<h4>Download results</h4>
<p>Downloadable log files are available as standard BLAST plain-text output 
files. Clicking on the top-left "download" button in the "results browser" page.</p>

<h4>Version, references and command line log:</h4>
<p>For the sake of consistency and traceability of the outcomes, information about 
the BLASTP method version implemented as well as the customary peer-reviewed 
references are given as part of the results page. For the sake of consistency, 
BlastApp also displays the blastall execution command-line undertaken by the 
server-side. 
</p>

</html>
<?php
print myfooter();
