<?php
include_once './Translator.php';

$t=new Translator(1);
echo $t->getWordValue($_POST['word']);