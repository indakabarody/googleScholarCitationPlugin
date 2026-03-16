# Google Scholar Citation Block Plugin for OJS

**Version:** 1.0.1.0

This plugin provides a sidebar block for Open Journal Systems (OJS) that displays an author's Google Scholar citation statistics. The plugin scrapes the author's public Google Scholar profile to fetch metrics (citations, h-index, i10-index) and a citation histogram, presenting them neatly in your journal's sidebar.

## Features

* **Real-time Statistics:** Directly fetches and parses data from Google Scholar.
* **Smart Caching:** To prevent API rate-limiting by Google and to speed up page load times, the scraper caches the fetched HTML content in the OJS database.
* **Customizable Update Frequency:** Choose between Daily, Weekly, or Monthly updates for the citation data.
* **Update Timestamps:** Displays exactly when the citation data was last updated underneath the widget.
* **Multilingual Support:** Fully localized in 24+ languages including English (`en_US`), Indonesian (`id_ID`), Spanish (`es`), French (`fr`), and many more.

## Requirements

* Open Journal Systems (OJS) 3.3.x.x
* PHP 7.3 or higher
* PHP configuration `allow_url_fopen` enabled (used by `file_get_contents` to fetch the external HTML).

## Installation

1. Download or clone this repository.
2. Place the extracted folder into the `plugins/blocks/` directory of your OJS installation. Note: Make sure the folder is named exactly `googleScholarCitationPlugin`.
3. Log in as a Site Administrator or Journal Manager.
4. Navigate to **Settings > Website > Plugins**.
5. Find "Google Scholar Citation Block" under the Block Plugins category and check the box to enable it.

## Configuration

1. After enabling the plugin, click the blue arrow next to the plugin name to reveal the **Settings** button.
2. Enter the **Google Scholar Profile URL**. 
   * Example: `https://scholar.google.com/citations?user=...`
3. Select your preferred **Update Frequency** (Daily, Weekly, or Monthly).
4. **Color Customization**: You can customize the look of the citation widget to match your journal's theme by using the built-in color pickers for:
   * **Histogram Color**: Changes the color of the citation bars.
   * **Label Color**: Changes the color of the year labels below the histogram.
5. Save your changes.

**Note:** Ensure the plugin is added to the sidebar display. You can verify this by going to **Settings > Website > Appearance**, scrolling down to **Sidebar management**, and making sure "Google Scholar Citation Block" is moved into the active Sidebar column.

## Troubleshooting

* **Data is not fetching / Error fetching data:** Verify that the server running OJS has internet access and can make outbound HTTP/HTTPS requests to `scholar.google.com`. Google Scholar may temporarily block the server's IP if too many requests are made too quickly (hence the caching feature).
* **Changes to Google Scholar URL aren't showing immediately:** Since the data is cached based on your update frequency setting, it may take up to a day / week / month for new data to appear. To force a refresh, simply re-save the plugin settings or clear the data/cache directory if heavily testing.

## License

This plugin is licensed under the GNU General Public License v3. See the `LICENSE` file for more details.
