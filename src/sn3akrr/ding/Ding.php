<?php namespace sn3akrr\ding;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\{
	EntityDamageEvent,
	EntityDamageByEntityEvent,
	EntityDamageByChildEntityEvent
};
use pocketmine\Player;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class Ding extends PluginBase implements Listener{

	public $sound;

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();

		$pk = new PlaySoundPacket();
		$pk->soundName = $this->getConfig()->get("sound", "random.orb");
		$pk->pitch = $this->getConfig()->get("pitch", 0.5);
		$pk->volume = $this->getConfig()->get("volume", 50);
		$this->sound = $pk;
	}

	public function onDmg(EntityDamageEvent $e){
		$entity = $e->getEntity();
		if($e instanceof EntityDamageByEntityEvent){
			$killer = $e->getDamager();
			if($killer instanceof Player){
				if($e instanceof EntityDamageByChildEntityEvent){
					$sound = $this->sound;
					$sound->x = $killer->x;
					$sound->y = $killer->y;
					$sound->z = $killer->z;
					$killer->dataPacket($this->sound);
				}
			}
		}
	}

}