<?php

$hash = '$2y$10$Yxq.1M6RrC5Z2gDe8pq3rOLxuo6SkMwCrEsplfijODa47EtK02htO';

if(password_verify('admin123', $hash))
{
    echo "Password Match";
}
else
{
    echo "Password Not Match";
}

?>