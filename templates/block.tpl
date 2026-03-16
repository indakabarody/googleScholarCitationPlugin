{**
 * plugins/blocks/googleScholarCitationPlugin/templates/block.tpl
 *
 * Copyright (c) 2014-2022 Simon Fraser University
 * Copyright (c) 2003-2022 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Common site sidebar block.
 *}

<style>
.block_google_scholar_citation {
	position: relative;
}

/* Title */
.block_google_scholar_citation .title {
	position: relative;
	display: flex;
	justify-content: space-between;
	align-items: center;
}

/* Header */
.block_google_scholar_citation .gs-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 8px;
}

/* Google Scholar Logo */
.block_google_scholar_citation .gs-logo-container {
	display: flex;
	align-items: center;
}

.block_google_scholar_citation .gs-logo-container img {
	width: 100px;
	height: auto;
	opacity: .9;
	transition: opacity .2s ease-in-out;
}

.block_google_scholar_citation .gs-logo-container img:hover {
	opacity: 1;
}

/* Update info */
.block_google_scholar_citation .gs-update-info {
	font-size: .85em;
	color: #777;
	text-align: right;
	line-height: 1.4;
}

/* Override Scholar default colors */
.block_google_scholar_citation .gsc_rsb_sth,
.block_google_scholar_citation .gsc_rsb_std,
.block_google_scholar_citation .gsc_tab_act,
.block_google_scholar_citation .gsc_tab_dis,
.block_google_scholar_citation .gsc_tab {
	color: #000 !important;
}

/* Hide unused elements */
.block_google_scholar_citation #gsc_hist_opn,
.block_google_scholar_citation .gsCitationDummy,
.block_google_scholar_citation .content h3.gsc_rsb_header {
	display: none;
}

/* Table styling */
.block_google_scholar_citation .content table {
	width: 100%;
	border-collapse: separate;
	border-spacing: 0;
	border: 1px solid #f3f3f3;
	box-shadow: 6px 9px 14px -13px rgba(193,193,193,.75);
	font-size: 13px;
}

.block_google_scholar_citation .content table th,
.block_google_scholar_citation .content table td {
	padding: 7px;
}

.block_google_scholar_citation .content table td {
	text-align: center;
}

.block_google_scholar_citation .content table td:first-child {
	text-align: left;
}

.block_google_scholar_citation .content table tr:nth-child(even) {
	background: #fbfbfb;
}

.block_google_scholar_citation .content a {
	color: #000;
	text-decoration: none;
}

/* Histogram wrapper */
.block_google_scholar_citation .gsc_g_hist_wrp {
	position: relative;
	padding-top: 10px;
	direction: ltr;
}

/* Histogram container */
.block_google_scholar_citation .gsc_md_hist_w {
	position: relative;
	height: 180px;
	width: 90%;
	margin: 15px 25px 25px 0;
	padding-bottom: 25px;
	overflow: hidden;
}

/* Histogram base */
.block_google_scholar_citation .gsc_md_hist_b {
	position: relative;
	height: 175px;
	width: 100%;
}

/* Grid lines */
.block_google_scholar_citation .gsc_g_hist_x {
	position: relative;
	top: 15px;
	right: 0;
	bottom: 30px;
	left: 0;
	z-index: 0;
}

.block_google_scholar_citation .gsc_g_x,
.block_google_scholar_citation .gsc_g_xt {
	position: absolute;
	left: 0;
	border-bottom: 1px solid #eee;
	width: 100%;
	text-align: right;
}

/* Y-axis labels */
.block_google_scholar_citation .gsc_g_hist_xl {
	position: relative;
	top: 3px;
	font-size: 11px;
}

.block_google_scholar_citation .gsc_g_xtl {
	position: absolute;
	color: #777;
	font-size: 11px;
}

/* Year labels */
.block_google_scholar_citation .gsc_g_t {
	position: absolute;
	bottom: -5px;
	color: #777;
	font-size: 10px;
	z-index: 2;
	white-space: nowrap;
}

/* Histogram bars */
.block_google_scholar_citation .gsc_g_a {
	position: absolute;
	bottom: 30px;
	width: 15px;
	background: linear-gradient(to bottom,#8095c7 0,#117D4B 100%);
	box-shadow: 1px 1px 2px rgba(0,0,0,.75);
	z-index: 1;
	transition: transform .15s ease;
}

.block_google_scholar_citation .gsc_g_a:hover {
	transform: scaleY(1.05);
}

.block_google_scholar_citation .gsc_g_a:hover,
.block_google_scholar_citation .gsc_g_a:focus,
.block_google_scholar_citation .gsc_g_a:active {
	text-decoration: none;
	cursor: default;
}

/* Citation number inside bar */
.block_google_scholar_citation .gsc_g_al {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	color: #fff;
	background: rgba(0,0,0,.6);
	font-size: 10px;
	padding: 2px 4px;
	border-radius: 3px;
	border: none;
	visibility: hidden;
	opacity: 0;
	transition: opacity .2s;
	z-index: 3;
}

/* Show number on hover */
.block_google_scholar_citation .gsc_g_a:hover .gsc_g_al {
	visibility: visible;
	opacity: 1;
}
</style>

<div class="pkp_block block_google_scholar_citation" id="customblock-gsCitation">

	<span class="title">
		{translate key="plugins.block.googleScholarCitation.blockTitle"}
	</span>

	<div class="content gs_content" style="padding-top: 5px;">

		<div class="gs-header">

			{if $updateFrequency}
			<div class="gs-update-info">

				{if $updateFrequency == 'daily'}
					{translate key="plugins.block.googleScholarCitation.updatedDaily"}
				{elseif $updateFrequency == 'weekly'}
					{translate key="plugins.block.googleScholarCitation.updatedWeekly"}
				{elseif $updateFrequency == 'monthly'}
					{translate key="plugins.block.googleScholarCitation.updatedMonthly"}
				{/if}

			</div>
			{/if}

			<div class="gs-logo-container">
				<a href="{$scholarUrl}" target="_blank">
					<img src="{$pluginUrl}/images/gs.png" alt="Google Scholar Logo" title="Google Scholar">
				</a>
			</div>

		</div>

		{$scholarContent}

	</div>

</div>
