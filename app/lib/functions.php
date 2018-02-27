<?php

function get($name, $def=''){
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $def;
}

function getID(){
    $file_name = 'ids';
    if(!file_exists($file_name)){
        touch($file_name); //sets the access and modification time of the specified file
        $handle = fopen($file_name, 'r+');
        $id = 0;
    }
    else{
        $handle = fopen($file_name, 'r+');
        $id = fread($handle,filesize($file_name));
        settype($id,"integer");
    }
    rewind($handle);
    fwrite($handle,++$id);
    fclose($handle);
    return $id;
}

function uploadImage($directory, $id)
{
    $target_file = $directory . basename($_FILES['picture']['name']);
    $uploadOk = 1;
    $image = '';
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    //Check if image
    if (isset($_POST["submit"]) && (isset($_POST['file']))) {
        $check = getimagesize($_FILES['picture']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }
    //Check if file exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }
    if ($_FILES['picture']['size'] > 6000000) {
        $uploadOk = 0;
    }
    //Allow certain formats
    if ($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "PNG"
        && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        if(file_exists($directory . $id . ".jpeg")) {
            $image = $id . ".jpeg";
        }else{
            $image = '';
        }
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
            rename($target_file, $directory . $id . ".jpeg");
            $image = $id . ".jpeg";
        } else {
            $image = '';
        }
    }
    return $image;
}
function deleteImage($img){
    if(file_exists($img)) {
        $a = unlink($img);
        return $a;
    }
}
function is_active($page, $current_link){
    return $page == $current_link ? 'active' : '';
}
//Displays table of contacts with first name, last name and picture
function displayContacts($file, $fName, $lName, $pic, $id, $persons){
    if(filesize($file)) {
        foreach ($persons as $person) {
            $person_fields = explode(',', $person);
            echo '<tr>';
            echo '<td>' . $person_fields[$fName] . '</td>';
            echo '<td>' . $person_fields[$lName] . '</td>';
            echo '<td style="text-align: right">';
                if($person_fields[$pic] != '') {
                    echo '<img src="/comp1230/assignment/cont/app/data/uploads/' . $person_fields[$pic] . '" alt="' . $person_fields[2] . ' ' . $person_fields[3] . '" class="picture">';
                }else{
                    echo "No image uploaded";
                }
            echo '</td>';
            echo '<td><a href="/comp1230/assignment/cont/public/?page=persons&action=delete&id=' . $person_fields[$id] . '" class="btn btn-outline-secondary" onclick="return confirm(\'Are you sure you want to delete the contact?\');">Delete</a></td>';
            echo '<td><a href="/comp1230/assignment/cont/public/?page=persons&action=update&id=' . $person_fields[$id] . '" class="btn btn-outline-secondary">Update</a></td>';
            echo '</tr>';
        }
    }else{
        echo 'No contacts to display';
    }
}
?>