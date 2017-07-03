#########################################################################
# File Name: commit.sh
# Author: 
# mail: 
# Created Time: 2017年07月03日 星期一 18时20分52秒
#########################################################################
#!/bin/bash
cd /var/www/html/think && git add . && git commit -m $1 && git push -u origin think
