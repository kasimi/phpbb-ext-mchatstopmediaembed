<?php

/**
 *
 * mChat Stop Media Embed. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kasimi\mchatstopmediaembed\event;

use phpbb\event\data;
use phpbb\textformatter\s9e\parser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
	/** @var bool */
	protected $is_mchat;

	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.message_parser_check_message'		=> 'set_mchat',
			'core.text_formatter_s9e_parse_before'	=> ['disable_mchat', 100],
		];
	}

	/**
	 * @param data $event
	 */
	public function set_mchat($event)
	{
		$this->is_mchat = $event['mode'] === 'mchat' || $event['mode'] === 'dmzx.mchat.text_reparser.mchat_messages';
	}

	/**
	 * @param data $event
	 */
	public function disable_mchat($event)
	{
		/** @var parser $service  */
		$service = $event['parser'];
		$parser = $service->get_parser();

		if ($this->is_mchat)
		{
			$parser->disablePlugin('MediaEmbed');
			$parser->disableTag('MEDIA');
		}
		else
		{
			$parser->enablePlugin('MediaEmbed');
			$parser->enableTag('MEDIA');
		}
	}
}
