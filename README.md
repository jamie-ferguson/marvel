# marvel

This application was developed and tested on a mac OS 10.13 (High Sierra) running php 7.1.7
After downloading a few set-up steps have to be taken...

1. Run composer install in the root directory.
2. Add the directory you wish to output the CSV file to - $csv_filepath variable at the top of public/index.php.
3. Add your Marvel public and private keys in the constructor of the Marvel class (app/models/Marvel.php).

A working example should be
$ php public/index.php Spider-Man comics