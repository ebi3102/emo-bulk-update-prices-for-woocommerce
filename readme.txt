=== Emo Bulk Update Prices for WooCommerce ===

Contributors: (ebi3102)
Tags: woocommerce, woocommerce add-on, update price, bulk update prices
Requires at least: 5.6
Tested up to: 5.8.3
Requires PHP: 8.0
Stable tag: 5.8.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A powerful system in bulk update WooCommerce products prices that additionally, is able to set discount in bulk way on WooCommerce products.

== Description ==

This plugin allows shop managers to update the WooCommerce products prices in bulk. Indeed, this feature is performable in two ways. The first is by uploading a csv file that contains the products ids and the new prices. The second is based on product categories. Furthermore, it is possible to place a discount on the products. Actually, this ability is done as like prices updating in two ways of file uploading and also based on product categories.

= Use case of Emo Bulk Update Prices for WooCommerce =

When economic conditions are unstable and prices are constantly changing, or when the number of products in the store is very large, managing prices and applying discounts through the edit page of each product is time-consuming. This plugin allows for easy price management.

= Features of Emo Bulk Update Prices for WooCommerce =

- The possibility of downloading the price list of products as a csv file.
- Inserting new prices in downloaded csv file and upload it.
- The possibility of increasing or decreasing prices based on product categories.
- The possibility of updating prices as a percentage or a fixed amount based on product categories.
- Placing discounts on products based on product categories according to their regular prices.
- Applying discounts on products as a percentage or a fixed amount based on product categories according to their regular prices.
- Ability to set the sale price date.

= Workflow of Emo Bulk Update Prices for WooCommerce =

= Minimum Requirements =

* Requires at least: 5.6
* Tested up to: 5.8.3
* Requires PHP: 8.0
* Stable tag: 5.8.0

== Installation ==

1. Upload ` emo-bulk-update-prices-for-woocommerce` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. In admin area from side menu click on `Prices management` to see other menus for different updating ways.

= Automatic installation =
The simplest way to install Emo Bulk Update Prices for WooCommerce is by using the automatic installation feature provided by WordPress. This method allows WordPress to handle the file transfers on your behalf, eliminating the need for you to exit your web browser. To perform an automatic installation of the plugin, log in to your WordPress dashboard, access the Plugins menu, and select Add New. Then, enter "Emo Bulk Update Prices for WooCommerce" into the search bar and click on the Search Plugins button. After locating the plugin, you can learn about its features and performance by checking its point release, rating, and description. The most crucial step, however, is to install the plugin by clicking on the "Install Now" button.

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.



== Frequently Asked Questions ==

= What kind of file extension we can use in this plugin for updating the prices? =

For updating the prices by file you must use .csv file. Also, these file is downloadable form the plugin in Update whole prices menu

== Reporting Security Issues ==
To disclose a security issue to our team put it [here](https://github.com/ebi3102/emo-woo-price-update/issues).

== Screenshots ==
1. Update prices by uploading CSV file: In this page the user is able to create and then download all products prices list. Also, in the second section the user is able to upload a csv file that contains the information of the products whose prices wants to update.
2. Group price update: In this page a user can update the prices (regular or on-sale) based on the product category.
3. Group discount: In this page a user can put discount on products based on the product category.


== Changelog ==

* 1.1
   * In this Version the plugin is rewrites based on Object-oriented programming with auto-loading
* 1.0
  * The first stable version for public release.
* 0.5
  * This version is a development version. Upgrade immediately.