<?php

// Base directories

# $base_dir = $_SERVER['DOCUMENT_ROOT'].'/BlastApplication';
$base_dir = '/home/dbw16/public_html/BlastApplication';
$base_URL = dirname($_SERVER['SCRIPT_NAME']);

// Temporal directory

$tmp_dir = $base_dir."/tmp";
if (!file_exists($tmp_dir)){
    mkdir($tmp_dir, 0744);
}

// Blast details, change to adapt to local settings as convenient
# $blast_home = "/usr/local/blast"; // localhost only
$blast_home = "/home/dbw00/blast"; // dbw server only
# $blast_db_dir = "$blast_home"; // localhost only
$blast_db_dir = "$blast_home/dbs"; // dbw server only
$blast_exe = "$blast_home/bin/blastall";
$blast_db = array('SwissProt' => 'sprot', 'PDB' => 'pdb_seqres.txt');
$blast_cmd_line = "$blast_exe -d $blast_db_dir/" . $blast_db['PDB'] . " -p blastp -e 0.001 -v 100 -b 0";
# the options are: -p program name; -e expectation value threshold; -v number of seqs to show descriptions for;
# -b number of seqs to show alignments for.

// Start session to store queries
session_start();
