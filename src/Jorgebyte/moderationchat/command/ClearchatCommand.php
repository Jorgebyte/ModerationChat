<?php

namespace  Jorgebyte\moderationchat\command;

use pocketmine\player\Player;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use Jorgebyte\moderationchat\Event;

class ClearchatCommand extends Command
{
    /** @var Event */
    private $event;

    public function __construct(Event $event)
    {
        parent::__construct("clearchat", "Use it to delete the chat", null, ["cc"]);
        $this->setPermission("moderationchat.command");
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
