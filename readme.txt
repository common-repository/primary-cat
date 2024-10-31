=== Primary Cat ===

Contributors: bhadaway
Donate link: https://calmestghost.com/donate
Tags: primary category, category, categories, category slug, breadcrumbs, seo, schema
Requires at least: 5.0
Tested up to: 6.3
Stable tag: 0.2
License: GPL
License URI: https://www.gnu.org/licenses/gpl.html

Set a primary category for your posts... and then do stuff with it. `[primary-cat]`

== Description ==

Set a primary category for your posts... and then do stuff with it.

**Why would I want a primary category?**

Out of the box, WordPress allows you to assign multiple categories to a post. Unless you're enforcing a strict one-category-per-post-only rule for your website, this can be a problem now for displaying the most important category for your respective posts, as opposed to WordPress automatically selecting it for you.

Let's say that your blog is about animals, and you publish an article titled "Why Do Dogs Love Water and Cats Hate It?" with the categories: *Cats*, *Dogs*, and *Pets* assigned to it.

By default, anywhere that WordPress might display a single category to represent the post, it's probably going to display *Cats* by default, but since the post is equally about both cats and dogs, maybe the more general *Pets* category would be more appropriate.

This plugin allows you to accomplish that, whether it's for presentational, structural, organizational, SEO, or all of the above purposes.

**In what ways can I utilize a primary category?**

`[primary-cat]`

In any creative way that you can imagine, but here are some examples I thought of:

* To present the main category that the post belongs to above its title (by adding `<?php echo do_shortcode( '[primary-cat]' ); ?>` in your theme code, child theme, or custom functions plugin), in some aesthetic way (which can be styled with CSS `.primary-cat`). Lots of blog, news, and magazine sites have this feature.
* To have the appropriate slug in your post URLs if you're using the custom `/%category%/%postname%/` permalink structure under *Settings > Permalinks*.
* To have breadcrumbs (by using the shortcode `[primary-bread]`) on your site for user-friendliness (which can be styled with CSS `#breadcrumbs`), and can in turn, also improve SEO.

== Frequently Asked Questions ==

= It's not working? =

* It's fully compatible with the classic editor, and I'm working on adding full support for the block editor too.
* You can enter whatever text you like into the Primary Category field to represent your post, and it's not even required to actually be a registered category. However, for certain features to work right, like overriding the category slug in URLs, it needs to also be an actual category.
* You may need to flush the permalinks by going to *Settings > Permalinks* and clicking on "Save Changes."
* You may need to clear any caches and refresh your browser.

== Installation ==

* Install and activate the plugin
* Create or edit a post
* You'll now be able to set a category as the primary category or add your own custom text
* Use the `[primary-cat]` shortcode on any page, post, or text widget or in your theme code, with `<?php echo do_shortcode( '[primary-cat]' ); ?>`

== Changelog ==

= 0.2 =
* Minor code cleanup

= 0.1 =
* New