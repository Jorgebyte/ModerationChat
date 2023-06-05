<?php

namespace moderation\chat\command;

use pocketmine\player\Player;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use moderation\chat\Event;

class ClearchatCommand extends Command
{
    public function __construct(Event $event)
    {
        parent::__construct("clearchat", "Use it to delete the chat", null, ["cc"]);
        $this->setPermission("clearchat.command");
        $this->event = $event;

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(count($args) == 0) {
            if($sender instanceof Player) {
                $this->event->clearChat();
            }
        }
        return true;
    }

}
