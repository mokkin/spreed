<?php
declare(strict_types=1);
/**
 *
 * @copyright Copyright (c) 2018, Daniel Calviño Sánchez (danxuliu@gmail.com)
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Talk\Files;

use OCA\Files\Event\LoadSidebar;
use OCA\Talk\AppInfo\Application;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\EventDispatcher\IEventListener;
use OCP\Util;

/**
 * Helper class to add the Talk UI to the sidebar of the Files app.
 */
class TemplateLoader implements IEventListener {

	public static function register(IEventDispatcher $dispatcher): void {
		$dispatcher->addServiceListener(LoadSidebar::class, TemplateLoader::class);
	}

	/**
	 * Loads the Talk UI in the sidebar of the Files app.
	 *
	 * This method should be called when handling the LoadSidebar event of the
	 * Files app.
	 */
	public function handle(Event $event): void {
		if (!($event instanceof LoadSidebar)) {
			return;
		}

		$config = \OC::$server->getConfig();
		if ($config->getAppValue('spreed', 'conversations_files', '1') !== '1') {
			return;
		}

		Util::addStyle(Application::APP_ID, 'merged-files');
		Util::addScript(Application::APP_ID, 'talk-files-sidebar');
		Util::addScript(Application::APP_ID, 'talk-files-sidebar-loader');

		// Needed to enable the screensharing extension in Chromium < 72.
		Util::addHeader('meta', ['id' => "app", 'class' => 'nc-enable-screensharing-extension']);
	}

}
