<?php 
/**
 * PHP Upload File from URL using PHP Copy()
 * @author: generasite
 * @link http://generasite.tk
 * @license: GNU GPL v2 or later
 */
    error_reporting(0);
    $timestart = microtime(TRUE);
    $timeend = microtime(TRUE);
    $time = $timeend - $timestart;
/* Source File URL */
    $get_url = $_POST['url'];
    $remote_file_url = trim($get_url);
/* New file name and path for this file */
    $local_file = basename($remote_file_url);
    $list_dir = ".";
    $getdir = $_POST['dirlist']; 

/* Generates a valid XHTML list of all directories in $directory */
function scan_directories($directory, $extensions = array()) {
/* Remove trailing slash */
    if ( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
    $code .= scan_directory($directory, $extensions);
    return $code;
}

/* Recursive function called by scan_directories() to list directories */
function scan_directory($directory, $extensions = array()) {
/* Get and sort directories */
    if ( function_exists("scandir") ) {
        $file = scandir($directory);
    }
    natcasesort($file);

/* Make directories first */
    $files = $dirs = array();
    foreach($file as $this_file) {
        if ( is_dir("$this_file" ) ) $dirs[] = $this_file; else $files[] = $this_file;
    }
    $file = array_merge($dirs, $files);
	
/* Filter unwanted extensions */
    if ( !empty($extensions) ) {
        foreach( array_keys($file) as $key ) {
            if ( !is_dir("$file[$key]") ) {
                $ext = substr($file[$key], strrpos($file[$key], ".") + 1);
                if ( !in_array($ext, $extensions) ) unset($file[$key]);
            }
        }
    }

        foreach( $file as $this_file ) {
                if( is_dir("$this_file") ) {

                    $scan_directories .= "<option value=\"".$this_file ."\">" . htmlspecialchars($this_file);
                    $scan_directories .= "</option>";

                }
        }
    return $scan_directories;
}

/* Copy the file from source url to server */
if( $remote_file_url ) {
    $valid_exts = array("php","jpeg","gif","png","doc","docx","jpg","html","asp","xml","JPEG","bmp","zip"); 
    $ext = end(explode(".",strtolower(basename($remote_file_url))));
        if(in_array($ext,$valid_exts)) {
            $newfile = fopen($getdir.'/'.$local_file, "wb");
                if($newfile) {
                    $copy = copy( $remote_file_url, $getdir.'/'.$local_file );

/* Add notice for success/failure */
if( !$copy ) {
    $status = '<span class="required">Error to copy.!</span>';
}
else{
    $status = '<span class="success">Success to copy.!</span>';
}
                }
                else {
                    $exists = '<span class="required">File does not exists.!</span>';
                }
        }
        else {
            $type = '<span class="required">Invalid Url.!</span>';
        }
}
else {
    $check_url = '<span class="required">Please enter the Url.!</span>';
}
?>

<!DOCTYPE html>
<head>
  <title>PHP Upload File from URL</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <style type="text/css">
    <!--
    body {
      font-family: Arial, serif;
      line-height: 150%;
    }
    h1 {
      font-size: 20px;
    }
    .form {
      max-width: 400px;
      padding: 20px 12px 10px 20px;
      font-size: 13px "Lucida Sans Unicode","Lucida Grande",sans-serif;
    }
    .form li {
      padding: 0;
      display: block;
      list-style: none;
      margin-bottom: 10px;
    }
    .form label {
      margin: 0 0 3px 0;
      padding: 0;
      display: block;
      font-weight: bold;
    }
    .form input[type=text],.form input[type=date],.form input[type=datetime],.form input[type=number],.form input[type=search],.form input[type=time],.form input[type=url],.form input[type=email],textarea,select {
      box-sizing: border-box;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      border: 1px solid #bebebe;
      padding: 7px;
      margin: 0;
      -webkit-transition: all .30s ease-in-out;
      -moz-transition: all .30s ease-in-out;
      -ms-transition: all .30s ease-in-out;
      -o-transition: all .30s ease-in-out;
      outline: 0;
    }
    .form input[type=text]:focus,.form input[type=date]:focus,.form input[type=datetime]:focus,.form input[type=number]:focus,.form input[type=search]:focus,.form input[type=time]:focus,.form input[type=url]:focus,.form input[type=email]:focus,.form textarea:focus,.form select:focus {
      -moz-box-shadow: 0 0 8px #88d5e9;
      -webkit-box-shadow: 0 0 8px #88d5e9;
      box-shadow: 0 0 8px #88d5e9;
      border: 1px solid #88d5e9;
    }
    .form .field-divided {
      width: 49%;
    }
    .form .field-long {
      width: 100%;
    }
    .form .field-select {
      width: 100%;
    }
    .form .field-textarea {
      height: 100px;
    }
    .form input[type=submit],.form input[type=button]
{
	background: #4b99ad;
	padding: 8px 15px 8px 15px;
	border: 0;
	color: #fff;
    }
    .form input[type=submit]:hover,.form input[type=button]:hover {
      background: #4691a4;
      box-shadow: none;
      -moz-box-shadow: none;
      -webkit-box-shadow: none;
    }
    .required {
      color: red;
    }
    .success {
      color: green;   
    }
    .status {
      margin-top: 20px;
      padding: 5px;
      font-size: 14px;
      background: #EEE;
      border: 1px dotted #DDD;
    }
    .copyright {
      margin-top: 20px;
      padding: 5px;
      font-size: 14px;
    }
    -->
  </style>
</head>
<body>
    <h1>PHP Upload File from URL</h1>
        <form action="" method="POST">
            <ul class="form">
                <li>
                    <label>Input Url <?php if ( ! file_exists($remote_file_url) || ! file_exists($newfile) || in_array($ext,$valid_exts) == false) {echo '<span class="required">*</span>'; } ?></label>
                    <input type="text" name="url" class="field-long"/>
              </li>
              <li>
                  <label>Select target directory <?php if ( ! file_exists($newfile)) { echo '<span class="required">*</span>'; }?></label>
                  <select name="dirlist" class="field-select">
                      <?php echo scan_directories("$list_dir"); ?>
                   </select>
               </li>
               <li>
                    <input type="submit" value="Submit"/>
                </li>
            </ul>
        </form>
    <div class="status">
        Status: <?php echo $check_url,$exists,$type,$status; ?>
    <br/>
        Processingtime: <?php echo $time; ?> ms
    </div>
    <div class="copyright">&copy;generasite</div>
</body>
</html>
