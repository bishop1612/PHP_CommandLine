# PHP_CommandLine

A PHP library that forks PHP processes onto the command line.

// ~PURPOSE~ //
Often php processes in the browser take a long time to execute. If a user sents a request to the server and decides to close the browser before receiving the request back from the server. The process will not be completed. Therefore it's important to come up with a certain that forks php processes in the background irrespective of user's actions. 

For example :
If a user wants a pdf emailed to her from the browser. She starts the process. If she quits the browser before the process is complete in the browser window the process will be terminated. 

Therefore I built this library which forks php processes onto the command line. I have use this in my work as well.


// ~GETTING STARTED ~//

There are two files exec_bg.php and bg_worker.php.

To implement this library it is very simple. All you have to do is pass the script path into the exec_bg function declared in the file exec_bg.php .

  /* SAMPLE CODE :
    <?php
      /*Temporary file that sets the php processes to command line*/
      $docRoot = "/home/www/ssl";	
      include_once($docRoot . '/oiscweb/lib/exec_bg/exec_bg.php');
      $script = $docRoot . "/oiscweb/projects/products/seed/exportquery.php";
      exec_bg($script);
    ?>
*/

Make sure to inculde the full script path


// ~HOW IT WORKS~ //
