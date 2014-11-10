sftp -b /dev/stdin <<++EOT++ -oPort=5513 -oIdentityFile=/home/medex/.ssh/id_dsa CMB@63.240.71.163
lcd /home/medex/cmbtest5
cd TEST
mget *
++EOT++
