# Simple Blog

I wanted a very simple blog that just read markdown files and displayed them newest to oldest. I also wanted some 
simple templating. So I made this in a night. It uses [Twig](https://github.com/twigphp/Twig) for templating and
[FrontYAML](https://github.com/mnapoli/FrontYAML) for parsing markdown.


# Installation

* Clone this repo to where you want your blog
* Run `composer install`
* Done

# Writing blog posts

Posts live in the `content` folder, and should be named like `url-for-post/post.md`. So, a post with a URL of 
`something-super-awesome` would live in the folder `content/something-super-awesome/post.md` Make sure to include 
frontmatter in YAML format (parseable by [FrontYAML](https://github.com/mnapoli/FrontYAML)) that has at least:
* date
* author
* title

# Templates

There are 4 templates:
* base.html - The base template that other templates extend
* index.html - The template that lists blog posts
* post.html - The template that displays a single blog post
* 404.html - The template that displays when a post can't be found

# Todo

* Add subdirectory support
* More stuff?
