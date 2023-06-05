<?php

namespace moderation\chat;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\utils\Config;

use moderation\chat\Event;
use moderation\chat\command\ClearchatCommand;

class Main extends PluginBase
{
    /** @var Config */
    public Config $config;

    /** @var array */
    public array $block = [];

    /** @var array */
    public array $words = [];

    /** @var Main $instance */
    public static Main $instance;

    /**
     * The configuration file name.
     */
    public const CONFIG_FILE = 'config.yml';

    public static function getInstance(): Main
    {
        return self::$instance;
    }

     public function onLoad(): void
     {
         self::$instance = $this;
     }

    public function onEnable(): void
    {
        $this->loadFiles();
        $this->loadEvent();
        $this->registerCommand();
    }

    private function loadFiles(): void
    {
        try {
            $this->saveResource(self::CONFIG_FILE);
            $this->config = new Config($this->getDataFolder() . self::CONFIG_FILE, Config::YAML);

            $this->words = $this->config->get("forbidden-words", []);
        } catch (\Exception $e) {
            $this->getLogger()->error("Error loading files: " . $e->getMessage());
        }
    }

    private function loadEvent(): void
    {
        $event = new Event($this);

        try {
            $this->getServer()->getPluginManager()->registerEvents($event, $this);
        } catch (\Exception $e) {
            $this->getLogger()->error("Error registering event: " . $e->getMessage());
        }
    }

    private function registerCommand(): void
    {
        $event = new Event($this);
        $this->getServer()->getCommandMap()->register("clearchat", new ClearchatCommand($event));
    }

}
