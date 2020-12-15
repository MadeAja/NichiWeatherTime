<?php

namespace MadeAja\NichiWeatherTime;

use MadeAja\NichiWeatherTime\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

class WtCommand extends PluginCommand
{
    /**
     * LayCommand constructor.
     * @param string $name
     * @param Main $plugin
     */
    private $main;

    public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name, $plugin);
        $this->setDescription("time command");
        $this->setUsage("/wt");
        $this->main = $plugin;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool|mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender->hasPermission("wt.command")){
            $sender->sendMessage(TextFormat::RED."You dont have permission");
            return true;
        }
        if (count($args) < 1) {
            $sender->sendMessage("Usage: /wt [start] [stop]");
            return true;
        }
        switch ($args[0]) {
            case "start":
                if ($this->main->config["enablewt"]) {
                    $sender->sendMessage(TextFormat::RED . "NichiWeatherTime Alredy Enable");
                    return true;
                }
                $this->main->config["enablewt"] = true;
                $this->main->startTime();
                $sender->sendMessage(TextFormat::GREEN . "NichiWeatherTime has been enabled");
                break;
            case "stop":
                if (!$this->main->config["enablewt"]) {
                    $sender->sendMessage(TextFormat::RED . "Already disabled!");
                    return true;
                }
                $this->main->config["enablewt"] = false;
                $this->main->stopTask();
                $sender->sendMessage(TextFormat::GREEN . "NichiWeatherTime has been disabled");
                break;
        }

        return true;
    }
}
