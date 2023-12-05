docker ps -q --filter ancestor=dispatcher-rhdcyberkit | xargs docker stop
docker ps -aq --filter ancestor=dispatcher-rhdcyberkit | xargs docker rm
echo "Premi enter per continuare"
read -p ""
docker image rm dispatcher-rhdcyberkit
echo "Premi enter per continuare"
read -p ""
docker build -t dispatcher-rhdcyberkit .
