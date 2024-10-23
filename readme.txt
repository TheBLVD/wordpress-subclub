=== sub.club ===
Contributors: subclub, bnolens, pfefferle
Tags: activitypub, sub.club, monetization
Requires at least: 5.0
Tested up to: 6.6
Requires PHP: 7.2
Stable tag: 1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Turn your free blog into a premium blog using sub.club.

== Description ==

A plugin that lets you earn money by turning your free blog into a premium blog using [sub.club](https://sub.club), the new, decentralized creator payments platform. Your premium blog posts will be on the Fediverse (aka ActivityPub), and your readers will be able to view them in Mastodon and Fediverse apps such as Ivory, Ice Cubes, Mammoth and many more. And of course readers can also just read premium posts on the web, using any web browser.

- **Service Website:** [https://sub.club](https://sub.club)
- **Terms of Use:** [https://melted-april-eb6.notion.site/Sub-club-Terms-of-Service-d468a66d0bb8442880b025d98e6a1549](https://melted-april-eb6.notion.site/Sub-club-Terms-of-Service-d468a66d0bb8442880b025d98e6a1549)
- **Privacy Policy:** [https://melted-april-eb6.notion.site/Sub-club-Privacy-Policy-ffeaae585c5244b79f1afd5b9684a4f2](https://melted-april-eb6.notion.site/Sub-club-Privacy-Policy-ffeaae585c5244b79f1afd5b9684a4f2)


== Other Notes ==

**Data Transmission**

When using this plugin, data is transmitted to api.sub.club when creating or editing posts.

Please ensure that you review sub.club's terms of use and privacy policy to understand how your data is handled and any legal implications.

**Legal Disclaimer**

By using this plugin, you agree to the terms and conditions of the third-party service. It is your responsibility to ensure compliance with any applicable laws and regulations regarding data transmission and privacy.

== Installation ==

= From your WordPress dashboard =

1. Navigate to `Plugins` -> `Add New`.
2. In the search field, type `sub.club` and click `Search Plugins`.
3. Once you have found the plugin, click `Install Now`.
4. After the plugin is installed, click `Activate`.
5. Go to [https://sub.club/login](https://sub.club/login) and set up your creator account. Make sure to configure Stripe!
6. Navigate to the Settings section in your WordPress admin dashboard.
7. Click on the sub.club settings section.
8. Enter your sub.club API key in the provided field and save the changes.

= From WordPress.org =

1. Download the `sub.club` plugin from the WordPress.org plugin repository.
2. Navigate to `Plugins` -> `Add New` in your WordPress dashboard.
3. Click `Upload Plugin` and choose the downloaded zip file.
4. Click `Install Now` and then `Activate`.
5. Go to [https://sub.club/login](https://sub.club/login) and set up your creator account. Make sure to configure Stripe!
6. Navigate to the Settings section in your WordPress admin dashboard.
7. Click on the sub.club settings section.
8. Enter your sub.club API key in the provided field and save the changes.

= Manual Installation =

1. Download the `sub.club` plugin from the WordPress.org plugin repository.
2. Unzip the downloaded file.
3. Upload the `subclub` directory to the `/wp-content/plugins/` directory on your web server.
4. Navigate to `Plugins` in your WordPress dashboard and activate the `sub.club` plugin.
5. Go to [https://sub.club/login](https://sub.club/login) and set up your creator account. Make sure to configure Stripe!
6. Navigate to the Settings section in your WordPress admin dashboard.
7. Click on the sub.club settings section.
8. Enter your sub.club API key in the provided field and save the changes.


== Frequently Asked Questions ==

= Does this plugin require any configuration? =

Yes. To use sub.club with WordPress, please follow these steps:

1. Install the sub.club WordPress plugin
2. Go to [https://sub.club/login](https://sub.club/login) and set up your creator account. Make sure to configure Stripe!
3. Navigate to the Settings section in your WordPress admin dashboard.
4. Click on the sub.club settings section.
5. Enter your sub.club API key in the provided field and save the changes.

= How do I create a premium post? =

1. When composing a new post in WordPress, you will see a `sub.club` meta box on the right-hand side of the editor.
2. In the `sub.club` meta box, you can choose to make the post a Premium post by selecting the appropriate option.
3. By doing this, a premium post will be created on sub.club, and the WordPress post will include a link to the premium post on sub.club.
4. Optionally, you can also include an excerpt that you defined while composing the post.

By following these steps, you can easily create and manage premium content on sub.club directly from your WordPress site.

= Can I edit a premium post? =

You can edit the except in your Wordpress post, but can not edit the premium content hosted on sub.club.
We currently do not support editing of premium posts using this plugin. We recommend to delete your Wordpress post and create a new premium post instead.
By default, we'll delete your sub.club post when you delete the related Wordpress post.

== Changelog ==

= 1.1 =
* Bug fixes and a11y improvements

= 1.0 =
* Initial release.
