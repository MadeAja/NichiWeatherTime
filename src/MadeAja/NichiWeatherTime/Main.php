<?php


namespace MadeAja\NichiWeatherTime;


use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{

    public $config;
    public $dataCuaca;

    public function onLoad()
    {
        foreach ($this->getResources() as $file){
            $this->saveResource($file->getFilename());
        }
       file_put_contents($this->getDataFolder()."config.yml", yaml_parse($this->config));
    }

    public function onEnable()
    {
       $this->getLogger()->info("NichiWeatherTime has been enable");
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
    public function setDataCuaca($dataCuaca)
    {
        $this->dataCuaca = $dataCuaca;
    }

    public function onDisable()
    {
        file_put_contents($this->getDataFolder()."config.yml", yaml_emit($this->config));
        sleep(1);
    }

}
