<?php

 $db = sqlite_open('./test.sqlite');

 $result = sqlite_array_query($db, 'SELECT user, password FROM users LIMIT 25', SQLITE_ASSOC);

 foreach ($result as $entry) 

     echo 'Name: ' . $entry['user'] . '  E-mail: ' . $entry['password'];

 ?>