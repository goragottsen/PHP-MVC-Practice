<?php
   //include "view.phtml";
    $current_view = $config['VIEW_PATH'] . 'persons' . DS;
    $target_dir = $config['DATA_PATH'] . 'uploads' . DS;
    $file = $config['DATA_PATH'] . 'persons.txt';

    switch(get('action')){
        case 'view':{
            $view = $current_view . 'view.phtml';
            $persons = file($file);
            break;
        }
        case 'add':{
            $view = $current_view . 'add.phtml';
            break;
        }
        case 'doAdd': {
            $newId = getID();
            $view = $current_view . 'view.phtml';
            $mode = 'w';
            if (file_exists($file)) {
                $mode = 'a';
            }
            $image = uploadImage($target_dir, $newId);
            $out = fopen($file, $mode) or die ('Cannot open the file');
            $persons = file($file);
            $comment = str_replace(PHP_EOL," ", get('comment'));
            $added_person = $newId . ',' . get('jTitle') . ',' .
                get('fName') . ',' . get('lName') . ',' . get('email') . ',' .
                get('site') . ',' . get('cellNumber') . ',' . get('homeNumber') . ',' .
                get('officeNumber') . ',' . get('twitterUrl') . ',' . get('facebookUrl') . ',' .
                $image . ',' . $comment . PHP_EOL;
            file_put_contents($file, $added_person, FILE_APPEND);
            @header('location: /comp1230/assignment/cont/public/?page=persons&action=view');
            break;
        }
        case 'delete':{
            //Use the delete.phtml page to implement unlink() function and to display confirmation upon deletion. Redirect to the contact list from there.
            $view = $current_view . 'delete.phtml';
            $id = get('id');
            $persons = file($file);
            foreach($persons as $index => $person){
                $persons_fields = explode(',', $person);
                if($persons_fields[0] == $id){
                    unset($persons[$index]);
                    deleteImage($target_dir . $id.".jpeg");
                    break;
                }
            }
            file_put_contents($file,implode('',$persons));
            @header('location: /comp1230/assignment/cont/public/?page=persons&action=view');
            break;
        }
        case 'update':{
            $view = $current_view . 'update.phtml';
            $persons = file($file);
            $id = get('id');
            $person_to_update = '';
            foreach($persons as $index => $person){
                $person_fields = explode(',', $person);
                if($person_fields[0] == $id){
                    $person_to_update = $person_fields;
                    break;
                }
            }
            break;
        }
        case 'doUpdate':{
            $id=get('id');
            $persons = file($file);
            $image = uploadImage($target_dir,$id);
            $comment = str_replace(PHP_EOL," ", get('comment'));
            $updated_person = $id . ',' . get('jTitle') . ',' .
                get('fName') . ',' . get('lName') . ',' . get('email') . ',' .
                get('site') . ',' . get('cellNumber') . ',' . get('homeNumber') . ',' .
                get('officeNumber') . ',' . get('twitterUrl') . ',' . get('facebookUrl') . ',' .
                $image . ',' . $comment . PHP_EOL;

            foreach($persons as $index => $person){
                $person_fields = explode(',',$person);
                if($person_fields[0] == $id){
                    $persons[$index] = $updated_person;
                    break;
                }
            }
            file_put_contents($file,implode('',$persons));
            //redirect the user after updating of the contact
            header('location: /comp1230/assignment/cont/public/?page=persons&action=view');
            break;
        }
    }
