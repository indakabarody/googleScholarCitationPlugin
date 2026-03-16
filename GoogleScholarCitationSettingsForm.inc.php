<?php

/**
 * @file plugins/blocks/googleScholarCitationPlugin/GoogleScholarCitationSettingsForm.inc.php
 *
 * Copyright (c) 2026 Indaka Barody
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class GoogleScholarCitationSettingsForm
 * @ingroup plugins_blocks_googleScholarCitationPlugin
 *
 * @brief Form for journal managers to modify Google Scholar Citation plugin settings
 */

import('lib.pkp.classes.form.Form');

class GoogleScholarCitationSettingsForm extends Form {

	/** @var int */
	protected $contextId;

	/** @var GoogleScholarCitationPlugin */
	protected $plugin;

	/**
	 * Constructor
	 * @param $plugin GoogleScholarCitationPlugin
	 * @param $contextId int
	 */
	function __construct($plugin, $contextId) {
		$this->contextId = $contextId;
		$this->plugin = $plugin;

		parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));

		$this->addCheck(new FormValidator($this, 'scholarUrl', 'required', 'plugins.block.googleScholarCitation.settings.form.scholarUrlRequired'));
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$updateFrequency = $this->plugin->getSetting($this->contextId, 'updateFrequency');
		$this->_data = array(
			'scholarUrl' => $this->plugin->getSetting($this->contextId, 'scholarUrl'),
			'updateFrequency' => $updateFrequency ? $updateFrequency : 'monthly',
			'histogramColor' => $this->plugin->getSetting($this->contextId, 'histogramColor'),
			'labelColor' => $this->plugin->getSetting($this->contextId, 'labelColor')
		);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('scholarUrl', 'updateFrequency', 'histogramColor', 'labelColor'));
	}

	/**
	 * Fetch the form.
	 * @param $request PKPRequest
	 * @param $template string
	 * @param $display boolean
	 * @return string HTML contents of the form
	 */
	function fetch($request, $template = null, $display = false) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->plugin->getName());
		
		$templateMgr->assign('updateFrequencyOptions', array(
			'daily' => __('plugins.block.googleScholarCitation.settings.form.updateFrequency.daily'),
			'weekly' => __('plugins.block.googleScholarCitation.settings.form.updateFrequency.weekly'),
			'monthly' => __('plugins.block.googleScholarCitation.settings.form.updateFrequency.monthly')
		));

		return parent::fetch($request, $template, $display);
	}

	/**
	 * Save settings. 
	 */
	function execute(...$functionArgs) {
		$this->plugin->updateSetting($this->contextId, 'scholarUrl', $this->getData('scholarUrl'), 'string');
		$this->plugin->updateSetting($this->contextId, 'updateFrequency', $this->getData('updateFrequency'), 'string');
		$this->plugin->updateSetting($this->contextId, 'histogramColor', $this->getData('histogramColor'), 'string');
		$this->plugin->updateSetting($this->contextId, 'labelColor', $this->getData('labelColor'), 'string');
		parent::execute(...$functionArgs);
	}
}
