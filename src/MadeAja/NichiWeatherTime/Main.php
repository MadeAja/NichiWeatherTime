<?php


namespace MadeAja\NichiWeatherTime\Main;


use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{

    public $config;

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


    public function onDisable()
    {
        file_put_contents($this->getDataFolder()."config.yml", yaml_emit($this->config));
    }

}