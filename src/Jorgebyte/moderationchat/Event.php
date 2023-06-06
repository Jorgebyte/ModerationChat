<?php

namespace Jorgebyte\moderationchat;

use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

use Jorgebyte\moderationchat\Main;

class Event implements Listener
{
    /** @var Main */
    public Main $plugin;

    /**
     * Event constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Handles the PlayerChatEvent.
     * @param PlayerChatEvent $event
     */
    public function onChat(PlayerChatEvent $event): void
    {
        $message = $event->getMessage();
        $player = $event->getPlayer();
        $name = $player->getName();

        if ($player->hasPermission("moderationchat.admin")) {
            return;
        }

        foreach ($this->plugin->words as $i) {
            if (strpos(" " . $message, $i)) {
                $player->sendMessage($this->plugin->config->getNested("forbidden-chat"));
                $event->cancel();
            }
        }

        if (!isset($this->plugin->block[$player->getName()])) {
            $this->plugin->block[$player->getName()] = time();
        }

        if (isset($this->plugin->block[$player->getName()])) {
            $time = ($this->plugin->block[$player->getName()] + $this->plugin->config->getNested("time")) - time();
            if ($time > 0) {
                $player->sendMessage($this->plugin->config->getNested("message-wait"));
                $event->cancel();
                return;
            }
            if ($time <= 0) {
                $this->plugin->block[$player->getName()] = time();
            }
        }
    }

    /**
     * Clears the chat for all players.
     * @return void
     */
    public function clearChat(): void
    {
        $players = Server::getInstance()->getOnlinePlayers();
        foreach ($players as $player) {
            for ($i = 0; $i < 50; $i++) {
                $player->sendMessage(" ");
            }
        }
        Server::getInstance()->broadcastMessage(Main::getInstance()->config->getNested("message-clearchat"));
    }
}
