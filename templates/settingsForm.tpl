{**
 * plugins/blocks/googleScholarCitationPlugin/templates/settingsForm.tpl
 *
 * Copyright (c) 2014-2022 Simon Fraser University
 * Copyright (c) 2003-2022 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Google Scholar Citation plugin settings form.
 *}
<script>
	$(function() {
		$('#googleScholarCitationSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	});
</script>
<form class="pkp_form" id="googleScholarCitationSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="blocks" plugin=$pluginName verb="settings" save=true}">
	{csrf}
	{include file="controllers/notification/inPlaceNotification.tpl" notificationId="googleScholarCitationSettingsFormNotification"}

	{fbvFormArea id="googleScholarCitationSettingsFormArea"}
		{fbvFormSection title="plugins.block.googleScholarCitation.settings.form.scholarUrl" description="plugins.block.googleScholarCitation.settings.form.scholarUrl.description"}
			{fbvElement type="text" id="scholarUrl" value=$scholarUrl required="true"}
		{/fbvFormSection}
		{fbvFormSection title="plugins.block.googleScholarCitation.settings.form.updateFrequency" description="plugins.block.googleScholarCitation.settings.form.updateFrequency.description"}
			{fbvElement type="select" id="updateFrequency" from=$updateFrequencyOptions selected=$updateFrequency translate=false}
		{/fbvFormSection}
	{/fbvFormArea}

	{fbvFormButtons}
</form>
