<?php

/*
Just testing, maybe phc-win will someday 
support phar archives.
Stub entry point for tikomatic application
This file is to be compiled using phc-win 
after the phar file is generated and copyed
into the build folder.
*/

echo "I am tikomatic.exe\n";

echo shell_exec( '.\php\php.exe -m' );

print_r( $argv );
array_shift($argv);
print_r( $argv );
$argstr =  implode(" ", $argv);
echo "argstr=$argstr\n";

echo shell_exec( '.\php\php.exe .\tikomatic.phar ' . $argstr );
