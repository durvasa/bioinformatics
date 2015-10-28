#!/usr/bin/perl

use strict;
use warnings;

my $line;
my $sequence;
my $protein;
my $counter = 0;
my $hits = 0;

# Open input file
open(INFILE, "<yeast.aa");

# Open output file
open(OUTFILE, ">results.txt");

#Initialize $sequence and read INFILE for the first time
$sequence='';
$line = <INFILE>;
while($line){
	chomp($line);
	if ($line =~ /^>/) {
			$counter++;
			if ($sequence =~ m/(C\w{2,4}C\w{3}[LIVMFYWC]\w{8}H\w{3,5}H)/g) {
			# symbol g after the binding operator sets the value of the binding expression 
			# equal to the number of matches of the regex, just in case it is needed
				print OUTFILE $protein, "\n";
				print OUTFILE $1, "\n";
				$hits++;
			}
			$protein = $line;
			$sequence = '';
	}
	else {
			$sequence = $sequence.$line;
	}
$line=<INFILE>; # continue to read the file: next line.
} # end while

# verify if the last sequence matches the regex
if ($sequence =~ m/(C\w{2,4}C\w{3}[LIVMFYWC]\w{8}H\w{3,5}H)/g) {
				print OUTFILE $protein, "\n";
				print OUTFILE $1, "\n";
				$hits++;
}

# number and ratio of proteins matching the regex
print $hits, "\n";
print $hits/$counter, "\n";

close(INFILE);
close(OUTFILE);
