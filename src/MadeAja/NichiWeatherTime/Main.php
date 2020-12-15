<?php


namespace MadeAja\NichiWeatherTime;


use MadeAja\NichiWeatherTime\Task\TaskUpdate;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{

    public $config;
    public $dataCuaca;
    private static $instance;

    public function onLoad()
    {
        foreach ($this->getResources() as $file){
            $this->saveResource($file->getFilename());
        }
       file_put_contents($this->getDataFolder()."config.yml", yaml_parse($this->config));
        self::$instance = $this;
    }

    public function onEnable()
    {
       $this->getLogger()->info("NichiWeatherTime has been enable");
       $this->getScheduler()->scheduleRepeatingTask(new TaskUpdate($this, $this->config['start_day_time'], $this->config['cityname'], $this->config['apikey']), 20 * (int)$this->config['updatetask']);
    }


    /**
     * @return mixed
     */
    public function getDataCuaca()
    {
        return $this->dataCuaca;
    }

    /**
     * @param mixed $dataCuaca
     */
    public function updateDataCuaca($dataCuaca)
    {
        $this->dataCuaca = $dataCuaca;
    }

     public static function getInstance(): Main
    {
        return self::$instance;
    }

    public function onDisable()
    {
        file_put_contents($this->getDataFolder()."config.yml", yaml_emit($this->config));
        sleep(1);
    }
}
