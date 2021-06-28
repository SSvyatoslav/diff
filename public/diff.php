<?php
include "Diff.php";

if($_FILES['inpFile']['name']){

    $targetPatch = "upload/" . basename($_FILES['inpFile']['name']);
    move_uploaded_file($_FILES['inpFile']['tmp_name'], $targetPatch);

}else{
    $diff = new Diff();

    $files_post = explode(",", $_POST['files']);

    $files_= $diff->take_files($files_post);

    $first_file = $files_[0];
    $second_file = $files_[1];
    $result = [];

    $files['first_file'] = $first_file;
    $files['second_file'] = $second_file;

    $result = $diff->differend($first_file, $second_file);
    ksort($result);
    $result = array_combine(range(1, count($result)), $result);
    $result['files'] = $files;

    header("Content-type: application/json; charset=UTF-8");
    header("Cache-Control: must-revalidate");
    header("Pragma: no-cache");
    header("Expires: -1");
    print json_encode($result);
}


