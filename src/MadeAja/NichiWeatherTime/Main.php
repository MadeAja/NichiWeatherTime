<?php


namespace MadeAja\NichiWeatherTime;


use MadeAja\NichiWeatherTime\WtCommand;
use MadeAja\NichiWeatherTime\Task\TaskUpdate;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{

    public $config;
    private static $instance;
    private $taskID;

    public function onLoad()
    {
        foreach ($this->getResources() as $file) {
            $this->saveResource($file->getFilename());
        }
        $this->config = yaml_parse(file_get_contents($this->getDataFolder() . "config.yml"));
        self::$instance = $this;
    }

    public function onEnable()
    {
        $this->getLogger()->info("NichiWeatherTime has been enable");
        $this->getServer()->getCommandMap()->register("wt", new WtCommand("wt", $this));
        if ($this->config['enablewt']) {
            $this->startTime();
        }
    }


    public function startTime(): void
    {
        $this->levelManagerTime(true);
        $ticks = (int)$this->config["start_day_time"];
        $ticks = min($ticks, 400);
        $start = $this->config["start_day_time"];
        if ($start >= 24 or $start < 0) {
            $this->getLogger()->notice("The day must not start on: " . $start . ". The day will be set to 8");
            $start = 8;
        }

        $task = $this->getScheduler()->scheduleRepeatingTask(new TaskUpdate($this, $start, $this->config['cityname'], $this->config['apikey']), $ticks);
        $this->taskID = $task->getTaskId();
    }


    public function stopTask()
    {
        $this->getScheduler()->cancelTask($this->taskID);
        $this->levelManagerTime(false);
    }

    public function levelManagerTime(bool $switchTime): void
    {
        foreach ($this->getServer()->getLevels() as $level) {
            if ($switchTime) {
                $level->stopTime();
            } else {
                $level->startTime();
            }
        }
    }


    public static function getInstance(): Main
    {
        return self::$instance;
    }
}
