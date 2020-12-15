<?php


namespace MadeAja\NichiWeatherTime\Task;


use MadeAja\NichiWeatherTime\Main;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class WeatherTreadTask extends AsyncTask
{
    /* @var string */
    private $zone;

    /* @var string */
    private $apikey;

    public function __construct($zone, $apikey)
    {
        $this->zone = $zone;
        $this->apikey = $apikey;
    }

    public function onRun()
    {
        $url = file_get_contents("https://rest.farzain.com/api/cuaca.php?id=".$this->zone."&apikey=".$this->apikey, true);
        $data = json_decode($url, true);
        $this->setResult(['status' => $data['status'], 'list' => $data]);
    }

    public function onCompletion(Server $server)
    {
        if($this->getResult()['status'] === 400){
            $this->getPlugin()->getScheduler()->cancelAllTasks();
            Server::getInstance()->shutdown();
            Server::getInstance()->getLogger()->warning("API KEY INVALID");
        }
            foreach ($server->getOnlinePlayers() as $player) {
                if ((string)$this->getResult()['list']['respon']['cuaca'] === "Rain") {
                    $pk = new LevelEventPacket();
                    $pk->evid = LevelEventPacket::EVENT_START_RAIN;
                    $pk->data = 100000;
                    $player->dataPacket($pk);
                }else{
                    $pk = new LevelEventPacket();
                    $pk->evid = LevelEventPacket::EVENT_STOP_RAIN;
                    $pk->data = 100000;
                    $player->dataPacket($pk);
                }
            }

        }

        public function getPlugin(): Main{
        return Main::getInstance();
    }

}