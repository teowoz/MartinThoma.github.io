TODO
=====

* add pagination for category pages / tag pages
* Semantic anchors created by TOC
* Newlines
  - e.g. 2013-03-16-c-puzzle-3.html (http://martinthoma.github.io/c-puzzle-3/)
* Don't check for Latex ($...$) inside of highlight environment or
  fenced code blocks (Abostrophes):
  - http://martinthoma.github.io/c-puzzle-3/
  - http://martin-thoma.com/plotting-graphs-with-pgfplots-latex-and-tikz/
  - http://martin-thoma.com/semantische-sicherheit/
  - http://martin-thoma.com/how-to-search-for-mathematical-symbols-in-latex/
  - http://martin-thoma.com/latex-versioning-a-great-experience/
  - All posts tagged with "PHP"
* Markdown rendered within fenced code tag: http://martin-thoma.com/how-to-print-source-code-with-latex/
* Fix posts:
  - http://martin-thoma.com/comparing-dates-in-php-and-mysql/
  - http://martin-thoma.com/algorithmen-ii-klausur/ (LaTeX)
  - http://martin-thoma.com/solving-linear-equations-with-gaussian-elimination/ (LaTeX at bottom)
* Apply CSS Rules from [CSS Wizardry](https://github.com/csswizardry/CSS-Guidelines)

Improve
========
* Site speed
  * Minify results
  * Combine CSS / JS files
  * Remove JS files if possible
  * Remove parts of JS that are not used
* Excerpts

Testen
=======
* WordPress "Caption" tags
* RSS Feed

Required
========
First:

    sudo apt-get install rubygems ruby-dev
    sudo juicer install yui_compressor

Although there is a `jekyll` package on Debian-Systems, you should not install it. Rather do it this way:

Then

    sudo gem install juicer jekyll dimensions
    sudo juicer install jslint

Install
========

See http://martin-thoma.com/jekyll-and-git/