#! /bin/sh

rsync -urvt -e ssh root@www.cuantum.me:/var/www/cuantum/. ../
