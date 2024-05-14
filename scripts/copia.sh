#! /bin/sh

rsync -urvt  -e ssh root@www.cuantum.me:/var/www/cuantum/imatges/. ../imatges/
rsync -urvt  -e ssh root@www.cuantum.me:/var/www/cuantum/gestio/db/. ../gestio/db/
rsync -urvt --delete -e ssh ../. root@www.cuantum.me:/var/www/cuantum/
