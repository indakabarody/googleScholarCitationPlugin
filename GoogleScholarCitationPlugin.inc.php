<?php

/**
 * @file plugins/blocks/googleScholarCitationPlugin/GoogleScholarCitationPlugin.inc.php
 *
 * Copyright (c) 2014-2022 Simon Fraser University
 * Copyright (c) 2003-2022 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class GoogleScholarCitationPlugin
 * @ingroup plugins_blocks_googleScholarCitationPlugin
 *
 * @brief Class for Google Scholar Citation block plugin
 */

import('lib.pkp.classes.plugins.BlockPlugin');

class GoogleScholarCitationPlugin extends BlockPlugin {

	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return __('plugins.block.googleScholarCitation.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return __('plugins.block.googleScholarCitation.description');
	}

	/**
	 * @copydoc Plugin::register()
	 */
	function register($category, $path, $mainContextId = null) {
		$success = parent::register($category, $path, $mainContextId);
		if ($success && $this->getEnabled($mainContextId)) {
			// Do something when the plugin is enabled
		}
		return $success;
	}

	/**
	 * Get the HTML contents for this block.
	 * @param $templateMgr object
	 * @param $request PKPRequest
	 * @return $string
	 */
	function getContents($templateMgr, $request = null) {
		$context = $request->getContext();
		if (!$context) return '';

		$templateMgr->assign('pluginUrl', $request->getBaseUrl() . '/' . $this->getPluginPath());

		$scholarUrl = $this->getSetting($context->getId(), 'scholarUrl');
		$updateFrequency = $this->getSetting($context->getId(), 'updateFrequency');
		if (!$updateFrequency) $updateFrequency = 'monthly';
		
		$lastUpdate = $this->getSetting($context->getId(), 'lastUpdate');
		$scholarContent = $this->getSetting($context->getId(), 'scholarContent');

		if (empty($scholarUrl)) {
			$templateMgr->assign('scholarContent', __('plugins.block.googleScholarCitation.notConfigured'));
			return parent::getContents($templateMgr, $request);
		} else {
			$templateMgr->assign('scholarUrl', $scholarUrl);
		}

		$needsUpdate = false;
		$currentTime = time();
		if (empty($scholarContent) || empty($lastUpdate)) {
			$needsUpdate = true;
		} else {
			$duration = 2592000; // monthly
			if ($updateFrequency === 'daily') {
				$duration = 86400;
			} elseif ($updateFrequency === 'weekly') {
				$duration = 604800;
			}
			
			if (($currentTime - $lastUpdate) > $duration) {
				$needsUpdate = true;
			}
		}

		if ($needsUpdate || $request->getUserVar('refreshScholar')) {
			$htmlContent = $this->_fetchScholarData($scholarUrl);
			if ($htmlContent !== false) {
				$scholarContent = $htmlContent;
				$lastUpdate = $currentTime;
				$this->updateSetting($context->getId(), 'scholarContent', $scholarContent, 'string');
				$this->updateSetting($context->getId(), 'lastUpdate', $lastUpdate, 'int');
			}
		}

		if (empty($scholarContent)) {
			$templateMgr->assign('scholarContent', __('plugins.block.googleScholarCitation.errorFetching'));
		} else {
			$templateMgr->assign('scholarContent', $scholarContent);
			$templateMgr->assign('updateFrequency', $updateFrequency);
			
			if ($lastUpdate) {
				$dateFormatShort = Config::getVar('general', 'datetime_format_short');
				if (!$dateFormatShort) $dateFormatShort = 'Y-m-d H:i:s';
				$templateMgr->assign('lastUpdateFormatted', date($dateFormatShort, $lastUpdate));
			}
		}

		return parent::getContents($templateMgr, $request);
	}

	private function _fetchScholarData($url) {
		// Use file_get_contents with a context to provide a User-Agent
		// Google Scholar might block requests without a proper User-Agent
		$options = [
			'http' => [
				'method' => 'GET',
				'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"
			]
		];
		$context = stream_context_create($options);
		
		$html = @file_get_contents($url, false, $context);
		
		if ($html === false) {
			return false;
		}

		// Parse the HTML using DOMDocument
		$dom = new DOMDocument();
		// Suppress warnings due to malformed HTML in scraped page
		@$dom->loadHTML($html);

		$xpath = new DOMXPath($dom);

		// Extract the specific header, table, and also the histogram graph wrapper if present
		$headerNodes = $xpath->query('//h3[contains(@class, "gsc_rsb_header")]');
		$tableNodes = $xpath->query('//table[@id="gsc_rsb_st"]');
		
		// Histogram wrapper
		$histNodes = $xpath->query('//div[contains(@class, "gsc_g_hist_wrp")]');

		$extractedHtml = '';

		if ($headerNodes->length > 0) {
			$extractedHtml .= $dom->saveHTML($headerNodes->item(0));
		}

		if ($tableNodes->length > 0) {
			$extractedHtml .= $dom->saveHTML($tableNodes->item(0));
		}
		
		if ($histNodes->length > 0) {
			$extractedHtml .= $dom->saveHTML($histNodes->item(0));
		}

		if (empty($extractedHtml)) {
			return false;
		}

		return $extractedHtml;
	}

	/**
	 * @copydoc Plugin::getActions()
	 */
	function getActions($request, $verb) {
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		return array_merge(
			$this->getEnabled() ? array(
				new LinkAction(
					'settings',
					new AjaxModal(
						$router->url($request, null, null, 'manage', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'blocks')),
						$this->getDisplayName()
					),
					__('manager.plugins.settings'),
					null
				),
			) : array(),
			parent::getActions($request, $verb)
		);
	}

	/**
	 * @copydoc Plugin::manage()
	 */
	function manage($args, $request) {
		switch ($request->getUserVar('verb')) {
			case 'settings':
				$context = $request->getContext();
				AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON,  LOCALE_COMPONENT_PKP_MANAGER);
				$templateMgr = TemplateManager::getManager($request);
				$templateMgr->register_function('plugin_url', array($this, 'smartyPluginUrl'));

				$this->import('GoogleScholarCitationSettingsForm');
				$form = new GoogleScholarCitationSettingsForm($this, $context->getId());

				if ($request->getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						$notificationManager = new NotificationManager();
						$notificationManager->createTrivialNotification($request->getUser()->getId(), NOTIFICATION_TYPE_SUCCESS, array('contents' => __('common.changesSaved')));
						return new JSONMessage(true);
					} else {
						return new JSONMessage(true, $form->fetch($request));
					}
				} else {
					$form->initData();
					return new JSONMessage(true, $form->fetch($request));
				}
		}
		return parent::manage($args, $request);
	}
}
