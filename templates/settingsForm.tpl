{**
 * plugins/blocks/googleScholarCitationPlugin/templates/settingsForm.tpl
 *
 * Copyright (c) 2026 Indaka Barody
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
		{fbvFormSection title="plugins.block.googleScholarCitation.settings.form.histogramColor" description="plugins.block.googleScholarCitation.settings.form.histogramColor.description"}
			<input type="color" name="histogramColor" id="histogramColor" value="{if $histogramColor}{$histogramColor|escape}{else}#117D4B{/if}" />
		{/fbvFormSection}
		{fbvFormSection title="plugins.block.googleScholarCitation.settings.form.labelColor" description="plugins.block.googleScholarCitation.settings.form.labelColor.description"}
			<input type="color" name="labelColor" id="labelColor" value="{if $labelColor}{$labelColor|escape}{else}#777777{/if}" />
		{/fbvFormSection}
	{/fbvFormArea}

	{fbvFormButtons}
</form>
