<?php

//if(file_exists('./screenlog.0')) {
//  unlink('./screenlog.0');
//}

exec("screen -dmSL test bash");
sleep(1);
exec("screen -S test -p 0 -X stuff 'echo test1\n'");
sleep(1);
exec("screen -S test -p 0 -X stuff 'echo test2\n'");
sleep(1);
exec("screen -S test -p 0 -X stuff 'echo test3\n'");
sleep(1);
exec("screen -S test -p 0 -X stuff 'ls\n'");
sleep(1);
exec("screen -S test -p 0 -X stuff 'exit\n'");
