<picture>
  <source media="(prefers-color-scheme: dark)" srcset="././assets/logo-dark.svg">
  <img alt="Sub.club" src="./assets/logo-light.svg">
</picture>

# Wordpress Plugin

A Wordpress plugin for interacting with [sub.club](https://sub.club).

## Installation

### From your WordPress dashboard

1. Navigate to `Plugins` -> `Add New`.
2. In the search field, type `sub.club` and click `Search Plugins`.
3. Once you have found the plugin, click `Install Now`.
4. After the plugin is installed, click `Activate`.
5. Go to [https://sub.club/login](https://sub.club/login) and set up your creator account. Make sure to configure Stripe!
6. Navigate to the Settings section in your WordPress admin dashboard.
7. Click on the sub.club settings section.
8. Enter your sub.club API key in the provided field and save the changes.

### From WordPress.org

1. Download the `sub.club` plugin from the WordPress.org plugin repository.
2. Navigate to `Plugins` -> `Add New` in your WordPress dashboard.
3. Click `Upload Plugin` and choose the downloaded zip file.
4. Click `Install Now` and then `Activate`.
5. Go to [https://sub.club/login](https://sub.club/login) and set up your creator account. Make sure to configure Stripe!
6. Navigate to the Settings section in your WordPress admin dashboard.
7. Click on the sub.club settings section.
8. Enter your sub.club API key in the provided field and save the changes.

### Manual Installation

1. Download the `sub.club` plugin from the WordPress.org plugin repository.
2. Unzip the downloaded file.
3. Upload the `subclub` directory to the `/wp-content/plugins/` directory on your web server.
4. Navigate to `Plugins` in your WordPress dashboard and activate the `sub.club` plugin.
5. Go to [https://sub.club/login](https://sub.club/login) and set up your creator account. Make sure to configure Stripe!
6. Navigate to the Settings section in your WordPress admin dashboard.
7. Click on the sub.club settings section.
8. Enter your sub.club API key in the provided field and save the changes.

## Usage

Once you have installed and activated the sub.club plugin, follow these steps to use it:

1. **Make sure you set your sub.club API Key:**

   - Navigate to the `Settings` section in your WordPress admin dashboard.
   - Click on the `sub.club` settings section.
   - Enter your sub.club API key in the provided field and save the changes.

2. **Create a Premium Post:**

   - When composing a new post in WordPress, you will see a `sub.club` meta box on the right-hand side of the editor.
   - In the `sub.club` meta box, you can choose to make the post a Premium post by selecting the appropriate option.
   - By doing this, a premium post will be created on sub.club, and the WordPress post will include a link to the premium post on sub.club.
   - Optionally, you can also include an excerpt that you defined while composing the post.

By following these steps, you can easily create and manage premium content on sub.club directly from your WordPress site.
