StaticSnap 🚀
Turn your WordPress site into a static speed demon!

StaticSnap Logo (Pretend this is a cool logo of a rocket-powered snail because SPEED + STATIC = 🐌🚀)

What is StaticSnap? 🤔
StaticSnap is the lazy developer’s dream plugin for WordPress. It automatically converts your dynamic posts and pages into static HTML files. Why? Because your server deserves a break, and your visitors deserve lightning-fast load times. ⚡

Think of it as WordPress on a diet—no more bloated database queries, no more PHP processing, just pure, unadulterated HTML. 🥗

How Does It Work? 🛠️
You Write Stuff: You create or update a post/page in WordPress.

StaticSnap Watches: Like a creepy but helpful neighbor, it notices. 👀

Magic Happens: It generates a static HTML file and saves it in /wp-content/static/.

Boom!: When someone visits your site, they get served the static file instead of hitting the database. 🎉

Why Use StaticSnap? 🏆
Speed: Your site will load faster than a caffeinated cheetah. 🐆☕

Security: Hackers can’t break what doesn’t exist (no PHP = no vulnerabilities). 🔒

Simplicity: No settings, no fuss, just install and forget. Set it and forget it, like a crockpot. 🍲

Eco-Friendly: Uses less server resources, saving the planet one static file at a time. 🌍

Installation 🛠️
Step 1: Download the Plugin
Option A: Download the latest release and upload it to your WordPress site.

Option B: Clone this repo into your /wp-content/plugins/ folder:

bash
Copy
git clone https://github.com/yourname/staticsnap.git  
Step 2: Activate the Plugin
Go to Plugins → Installed Plugins.

Find StaticSnap and click Activate.

Celebrate with a cup of coffee. ☕

Usage 🎮
Automatic Static Generation
StaticSnap works automagically! 🪄

Create or update a post/page → Static HTML is generated.

Trash a post → Static HTML is deleted.

Manual Regeneration
Want to regenerate all static files? Use WP-CLI:

bash
Copy
wp staticsnap generate  
This will rebuild all static files faster than you can say, “Why didn’t I do this sooner?”

Exclude Posts/Pages
Don’t want a specific post to be static? Add this to your theme’s functions.php:

php
Copy
add_post_meta($post_id, '_staticsnap_excluded', true);  
Or use a filter to exclude posts with a specific tag:

php
Copy
add_filter('staticsnap_skip_post', function($skip, $post_id) {  
    if (has_tag('no-static', $post_id)) {  
        return true;  
    }  
    return $skip;  
}, 10, 2);  
How It Works Under the Hood 🕵️
The Lifecycle of a Static File
Post Saved: StaticSnap listens for the save_post hook.

HTML Rendered: It simulates a frontend request to generate the HTML.

File Saved: The HTML is saved to /wp-content/static/[post-slug].html.

Request Served: When someone visits the post, StaticSnap serves the static file instead of hitting the database.

Rewrite Rules
StaticSnap adds rules to your .htaccess (or NGINX config) to serve static files. If the file doesn’t exist, it falls back to WordPress.

FAQ ❓
Q: Will this break my site?
A: Only if you use it wrong. But seriously, test it on a staging site first.

Q: Can I use this with caching plugins?
A: Sure, but why? StaticSnap is already faster than your ex’s new relationship.

Q: What if I delete the plugin?
A: Your static files will still exist, but they won’t be served. Clean up by deleting /wp-content/static/.

Contributing 🤝
Want to make StaticSnap even better? Here’s how:

Fork the repo.

Create a branch: git checkout -b feature/amazing-feature.

Commit your changes: git commit -m 'Add amazing feature'.

Push: git push origin feature/amazing-feature.

Open a pull request.

License 📜
StaticSnap is licensed under GPLv2. That means you can use, modify, and share it freely. Just don’t blame us if your site becomes too fast and breaks the internet.

Credits 🙌
Developed by: Your Name (aka the lazy genius).

Inspired by: Coffee, procrastination, and a hatred for slow websites.

Final Words 🎤
StaticSnap is the plugin you never knew you needed. It’s fast, it’s simple, and it’s here to make your life easier. So go ahead, give it a try. Your server will thank you, your visitors will thank you, and I’ll thank you for reading this far.

Now go forth and snap those static files! 🚀