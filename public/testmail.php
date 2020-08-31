<?php

// the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("ahmed.rafat147@gmail.com","My subject",$msg);

echo "DOne !";

if( function_exists("proc_open")){
    echo "Hi !";
}