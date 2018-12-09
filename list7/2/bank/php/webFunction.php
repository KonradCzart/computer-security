<?php
function printError($sessionValue){
  if(isset($_SESSION[$sessionValue])){
    echo '<div class="error">'.$_SESSION[$sessionValue].'</div>';
    unset($_SESSION[$sessionValue]);
  }
}

function saveValue($sessionValue){
  if(isset($_SESSION[$sessionValue])){
    echo $_SESSION[$sessionValue];
    unset($_SESSION[$sessionValue]);
  }
}



?>
