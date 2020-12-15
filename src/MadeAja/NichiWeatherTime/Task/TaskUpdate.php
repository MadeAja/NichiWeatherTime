<?php


namespace MadeAja\NichiWeatherTime\Task;


use MadeAja\NichiWeatherTime\Main;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class TaskUpdate extends Task
{

    private $plugin;
    private $apikey;
    private $zone;
    private $daystart;

    public function __construct(Main $plugin, $daystart, $zone, $apikey)
    {
        $this->plugin = $plugin;
        $this->zone = $zone;
        $this->apikey = $apikey;
        $this->daystart = $daystart;
    }

    public function onRun(int $currentTick)
    {

        $day = date("N");
        $hours = date("H", time() - ($this->daystart * 3600));
        $mins = date("i");
        $secs = date("s");
        $secondsTime = ($hours * 3600) + ($mins * 60) + $secs;

        $tick = (int)floor($secondsTime / 86400 * 24000);
        $phase = ($day - 1) * 24000;
        if ($tick >= 24000 - 3000 * $day) {
            $phase += 24000;
        }
        foreach (Server::getInstance()->getLevels() as $level) {
            $level->setTime($tick + $phase);
        }
        $this->getPlugin()->getServer()->getAsyncPool()->submitTask(new WeatherTreadTask($this->zone, $this->apikey));
    }


    /**
     * @return Main
     */
    public function getPlugin(): Main
    {
        return $this->plugin;
    }

}