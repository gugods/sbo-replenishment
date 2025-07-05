1. Paste "sbo_replenishment.sql" file to  ~/sbo-replenishment/docker/mysql/db_data/
2. Paste "*.bson" file to  ~/sbo-replenishment/docker/mongodb/db_data/
3. Download "https://www.apachefriends.org/xampp-files/5.6.30/xampp-linux-x64-5.6.30-0-installer.run" 
	& Paste "xampp-linux-x64-5.6.30-0-installer.run" file to  ~/docker/web/
4. Open "Terminal" cd ~/sbo-replenishment/docker/ & sh install_server.sh
5. Open Browser "http://localhost:8000" Completed. 

.........................................................................................


#start service
Open "Terminal" cd ~/sbo-replenishment/docker/ & sh start_server.sh

#stop service
Open "Terminal" cd ~/sbo-replenishment/docker/ & sh stop_server.sh