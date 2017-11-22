=== Plugin Name ===
Contributors: _smartik_
Donate link: https://paypal.me/zerowp
Tags: social, facebook, twitter, google, widget, link, network, profile, user, member
Requires at least: 4.7
Tested up to: 4.9
Stable tag: 1.1.2
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Create links to profiles from 170+ social networks

== Description ==

### What you'll get after install:
* A form input to create links to social networks, on each user profile page in admin dashboard.
* "About user" - widget to display a user card profile with avatar, name, website... and social links.
* "Social profiles" - widget to display general social links. Useful to display site connections.

### The easiest way to add social links to:
* Add links to social networks in user profiles.
* Display links to social networks in a widget, in general.
* Display user cards in profile.
* Display user info box on a single post type. You'll have to place this code somewhere: `do_action( 'zsp:author_info' );`
* Display user info on author page. You'll have to place this code somewhere: `do_action( 'zsp:author_info' );`.

### Some key features:
Regarding the previous points here are some additional notes.
1. Two advanced widgets: Author box and Networks list.
2. Sortable links. You add the links to different social networks and you have the possibility to sort them using a drag-n-drop interface.
3. Multiple styles: different shapes, colors, sizes, list style, etc.
4. A large set of settings for user info boxes.
5. More, more, more.... You have to install it, **it's totally free**. ;)


== Installation ==
1. Upload the `zerowp-social-profiles` folder to the `/wp-content/plugins/` directory
2. Activate the 'Social Profiles by ZeroWP' plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.1.2 =
* Bug fix: The "Default" icon size produces incorrect icon shape format.

= 1.1.1 =
* WordPress 4.9 compatibility.
* Bug fix: Incompatibility of `sanitize` method from`SocialProfiles\GeneralForm\FieldBrandsRepeater` class and its parent."
* Improvement: The icon is not properly vertically aligned.

= 1.1.0 =
* Bug fix: The icons design broken if the current theme does not apply `box-sizing: border-box` CSS to all elements.
* Improvement: Better vertical align for networks list.
* Improvement: Added more security checks and sanitization to prevent bad user input.
* More hooks for developers.

= 1.0.0 =
* Initial release
