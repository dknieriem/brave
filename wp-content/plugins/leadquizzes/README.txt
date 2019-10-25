=== LeadQuizzes ===
Tags: quiz, lead generation, marketing
Donate link: https://leadquizzes.com
Requires at least: 3.5.1
Tested up to: 4.4.2
Stable tag: 4.4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

LeadQuizzes software plugin that makes it easy to take your quizzes and put them on your own Wordpress domain.

== Description ==
Most businesses struggle to generate high quality leads but LeadQuizzes has set out to change this. With LeadQuizzes you can capture contact information like name, email, and phone number before or after your quiz as well as capture leads through a web form or let your quiz takers submit their name and email through their Facebook login.

Never miss the perfect moment to ask for the sale. Putting a clever quiz on your website could help generate leads as well as make different product recommendations based on the user's responses. With the wordpress plugin and shortcode there are endless options for where to put your quiz and have it look and work great! 

== Installation ==
1. Download Plugin from Wordpress or Upload folder named `leadquizzes` to the `wp-content/plugins` directory in your website's FTP
2. Login to your Wordpress Admin and navigate to 'Plugins' in the left-hand side menu
3. Find LeadQuizzes in your list of plugins and activate the plugin by clicking 'activate'

== Frequently Asked Questions ==
HOW TO USE PLUGIN
-- What do I need to embed a quiz on my domain? --
First, you will need a LeadQuizzes account here: https://quiz.leadquizzes.com
Then, you need to create a quiz. When you have reached the final step titled 'Review & Publish' copy the 'Quiz URL' link you are given
Next, in your Wordpress admin dashboard select the LeadQuizzes plugin from the left-hand side menu and click 'Add New' toward the top next to the title of the plugin
Now, simply create a title and paste the link you copied from your LeadQuizzes dashboard
**note: the title you pick will be used to generate your new quiz URL ex: http://www.yourdomain.com/q/[title here]

-- Is this all I need to embed a quiz on my website? --
Yes, it is that easy :)

-- If I don't want the quiz to occupy an entire page is there another way to embed? --

Sure, use our shortcode!
Just copy and paste this code next to other content in your page (like at the end of a blog post),

[leadquiz url="your-quiz-url"]

and substitute your quiz link where it says "your-quiz-url".

There are few customization options for the LeadQuizzes shortcode. Add these after the url=" ", inside of the brackets [ ], like this:

[leadquiz url="your-quiz-url" class="custom-class"]

Here are the options:
height="200px" => You can specify the height for quiz box.
width="200px" => You can specify width for quiz box and quiz will fit in that box.
class="custom-class" => You can specify custom class to quiz box for custom styling.

Here is what it looks like with all the options added:

[leadquiz url="your-quiz-url" height="200px" width="200px" class="custom-class"]

== Changelog ==
1.0

1.1

- Removed popup option to normalize to new embedded modals