<?php


namespace MadeAja\NichiWeatherTime\Task;


use MadeAja\NichiWeatherTime\Main;
use pocketmine\scheduler\Task;

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