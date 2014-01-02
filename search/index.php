<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
  <!-- type: head.html -->
  <head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title>Search</title>
    <link rel="stylesheet" href="http://martin-thoma.com/css/201401020632.min.css" type="text/css" media="screen" />

    <link rel="alternate" type="application/rss+xml" title="Martin Thoma RSS Feed" href="http://martin-thoma.com/feed/" /><!--TODO-->
    <link rel="shortcut icon" href="http://martin-thoma.com/favicon.ico" type="image/x-icon" />

    <link rel="canonical" href="http://martin-thoma.com/search/index.php" />
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="@themoosemind"/>
    <meta name="twitter:domain" content="Martin Thoma"/>
    <meta name="twitter:creator" content="@themoosemind"/>

    

    
        <meta name="twitter:description" content="" />
    

    <meta name="twitter:title" content="Search - Martin Thoma"/>
    <meta name="twitter:url" content="http://martin-thoma.com/search/index.php"/>

    <script type='text/javascript' src="http://martin-thoma.com/js/jquery.js"></script>
    <script type='text/javascript' src="http://martin-thoma.com/js/jquery-migrate.min.js"></script>
    <style type="text/css">div#toc_container {width: 275px;}</style>
    <style type="text/css" id="syntaxhighlighteranchor"></style>
</head>

<!-- type: search.html -->
<body>
	<div id="wrapper">
		<div id="container" class="container">
			<div class="span-16">
                <!-- type: header.html -->
<div id="header">
    <h1><a href="http://martin-thoma.com">Martin Thoma</a></h1>
    <h2>A blog about Code, the Web and Cyberculture.</h2>
</div>
<div class="navcontainer">
	<ul id="nav">
		<li class=""><a href="http://martin-thoma.com">Home</a></li>
		<li class="page_item page-item-41 "><a href="http://martin-thoma.com/about-martin-thoma/">About Me</a></li>
        <li class="page_item page-item-91 "><a href="http://martin-thoma.com/imprint/">Imprint</a></li>
	</ul>
</div>

            	<div id="content">
                        <?php 
$db = new SQLite3('search.db', SQLITE3_OPEN_READONLY);

$query = $_GET['s'];

if ( $query == ""  || preg_match("/^\s+/",$query) ) {
    echo "<h2 class='pagetitle'>No query specified.</h2><div class='entry'>";
}    else {
    echo "<h2 class='pagetitle'>Search Results for '".$query."'</strong></h2><div class='entry'>";

    $query = preg_replace("/^\s+/", "", $query);
    $query = preg_replace("/\s+$/", "", $query);
    $query = preg_replace("/(\s+)(\w+)/", "% %", $query);
    $query = "%" . $query . "%" ;
  
    # Currently returns max of 50 results, count to be used for pagination etc
    $count_stmt = $db->prepare('SELECT count(*) as num_pages FROM pages WHERE title like :search or text_content like :search  or permalink like :search');
    $count_stmt->bindValue(':search', $query, SQLITE3_TEXT);
    $count = $count_stmt->execute();

    $count_result = $count->fetchArray();

    if ( $count_result['num_pages'] == 0){
        #echo "<p>No results for '$query'.</p>";
    } else {
        $results_text = ($count_result['num_pages'] == 1) ? 'result' : 'results';
        $max_results_text = ($count_result['num_pages'] > 50) ? 'Showing first 50 results of ' : '';
        #echo "<p>$max_results_text{$count_result['num_pages']} $results_text for '$query'.</p>";

        $stmt = $db->prepare('SELECT title, permalink, search_excerpt, featured_image, date FROM pages WHERE title like :search or text_content like :search  or permalink like :search LIMIT 50');
        $stmt->bindValue(':search', $query, SQLITE3_TEXT);

        $result = $stmt->execute();

        /* Show result entry */
        while($search_result = $result->fetchArray(SQLITE3_ASSOC)){
            ?>
	<div class="post type-post status-publish format-standard hentry clearfix">
        <h2 class="title"><a href="<?echo "{$search_result['permalink']}";?>" rel="bookmark" title="Permanent link to '<?echo "{$search_result['title']}";?>'"><?echo "{$search_result['title']}";?></a></h2>
		<div class="postdate">
            <span><?echo date("F jS, Y", strtotime($search_result['date']));?></span> 
            <!--<span>No Comments / 1 comment / 2 comments</span>-->
        </div>

        <div class="entry">
            <?if($search_result['featured_image'] != '') {?>
            <a href="http://martin-thoma.com/"><img width="128" height="128" src="http://martin-thoma.com/images/<?echo "{$search_result['featured_image']}";?>" class="alignleft post_thumbnail wp-post-image" alt=""/></a>
            <?}?>

	        <?echo "{$search_result['search_excerpt']}";?> [...]

            
            <div class="readmorecontent">
		        <a class="readmore" href="http://martin-thoma.com/" rel="bookmark" title="Permanent Link to ''">Read More &raquo;</a>
	        </div>
        </div>

        <!--TODO
        <div class="postmeta">
            Posted in 
            <a href="http://martin-thoma.com/category/code/" title="View all posts in Code" rel="category tag">Code</a> | 
            Tags: 
            <a href="http://martin-thoma.com/tag/c/" rel="tag">C</a>, <a href="http://martin-thoma.com/tag/puzzle/" rel="tag">puzzle</a> 
        </div> -->
	</div>
<?
        }
    }
}
?>
</div>

						<div class="clearfix">&nbsp;</div>
            	</div>
                <div class="navigation">
            	    <div class="alignleft">
                    
                    </div>
            		<div class="alignright">
                    
                    </div>
                </div>
        	</div>
            <div class="span-8 last">
                <div id="subscriptions">
<a href="http://martin-thoma.com/feed/"><img src="http://martin-thoma.com/css/images/rss.png" alt="Subscribe to RSS Feed" title="Subscribe to RSS Feed" /></a>		
<a href="https://twitter.com/#!/themoosemind" title="Follow me on Twitter!"><img src="http://martin-thoma.com/css/images/twitter.png" title="Follow me on Twitter!" alt="Follow me on Twitter!"  /></a>
</div>

	<div id="sidebar">
        <!-- type: searchbox.html - TODO-->
<ul>
    <li id="search">
        <div class="searchlayout">
            <form method="get" id="searchform" action="http://www.martin-thoma.de/search/">
                <input type="text" value="Search" 
                    name="s" id="s"  onblur="if (this.value == '')  {this.value = 'Search';}"  
                    onfocus="if (this.value == 'Search') {this.value = '';}" />
                <input type="image" src="http://martin-thoma.com/css/images/search.gif" style="border:0; vertical-align: top;" /> 
            </form>
        </div>
    </li>
</ul>

        <div class="addthis_toolbox">   
    <div class="custom_images">
            <a class="addthis_button_twitter"><img src="http://martin-thoma.com/css/images/socialicons/twitter.png" width="32" height="32" alt="Twitter" /></a>
            <a class="addthis_button_delicious"><img src="http://martin-thoma.com/css/images/socialicons/delicious.png" width="32" height="32" alt="Delicious" /></a>
            <a class="addthis_button_facebook"><img src="http://martin-thoma.com/css/images/socialicons/facebook.png" width="32" height="32" alt="Facebook" /></a>
            <a class="addthis_button_digg"><img src="http://martin-thoma.com/css/images/socialicons/digg.png" width="32" height="32" alt="Digg" /></a>
            <a class="addthis_button_stumbleupon"><img src="http://martin-thoma.com/css/images/socialicons/stumbleupon.png" width="32" height="32" alt="Stumbleupon" /></a>
            <a class="addthis_button_favorites"><img src="http://martin-thoma.com/css/images/socialicons/favorites.png" width="32" height="32" alt="Favorites" /></a>
            <a class="addthis_button_more"><img src="http://martin-thoma.com/css/images/socialicons/more.png" width="32" height="32" alt="More" /></a>
    </div>
    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js?pub=xa-4a65e1d93cd75e94"></script>
</div>

    					        
		<ul>
    <li id="categories-3" class="widget widget_categories">
        <!-- type: categories -->
<h2 class="widgettitle">Categories</h2>
    <ul>
        <li class="cat-item cat-item-11"><a href="http://martin-thoma.com/category/code/" title="Tipps for coding in different languages like Python oder C++.">Code</a></li>
        <li class="cat-item cat-item-21"><a href="http://martin-thoma.com/category/web/" title="New emerging websites and technologies.">The Web</a></li>
        <li class="cat-item cat-item-31"><a href="http://martin-thoma.com/category/cyberculture/" title="Lolcats, planking, Trollfaces, ...">Cyberculture</a></li>
        <li class="cat-item cat-item-3404"><a href="http://martin-thoma.com/category/maths/" title="View all posts filed under Mathematics">Mathematics</a></li>
        <li class="cat-item cat-item-881"><a href="http://martin-thoma.com/category/bits-and-bytes/" title="Sometimes posts don&#039;t fit in any category.">My bits and bytes</a></li>
        <li class="cat-item cat-item-41"><a href="http://martin-thoma.com/category/deutschland/" title="[All Posts here are written in German about German topics] - Die Bahn, unsere Politik und Europa.">German posts</a></li>
	</ul>

    </li>
    <li id="tag_cloud-3" class="widget widget_tag_cloud"><h2 class="widgettitle">Tags</h2><div class="tagcloud"><a style='font-size: 135.95%' href='http://martin-thoma.com/tag/theoretical computer science/' title='9 topics'>Theoretical computer science</a>
<a style='font-size: 90.00%' href='http://martin-thoma.com/tag/wikipedia/' title='5 topics'>Wikipedia</a>
<a style='font-size: 90.00%' href='http://martin-thoma.com/tag/advertising/' title='5 topics'>advertising</a>
<a style='font-size: 256.37%' href='http://martin-thoma.com/tag/programming/' title='42 topics'>Programming</a>
<a style='font-size: 248.55%' href='http://martin-thoma.com/tag/python/' title='38 topics'>Python</a>
<a style='font-size: 135.95%' href='http://martin-thoma.com/tag/google/' title='9 topics'>Google</a>
<a style='font-size: 218.88%' href='http://martin-thoma.com/tag/c/' title='26 topics'>C</a>
<a style='font-size: 126.74%' href='http://martin-thoma.com/tag/google code jam/' title='8 topics'>Google Code Jam</a>
<a style='font-size: 158.44%' href='http://martin-thoma.com/tag/linux/' title='12 topics'>Linux</a>
<a style='font-size: 90.00%' href='http://martin-thoma.com/tag/ubuntu/' title='5 topics'>Ubuntu</a>
<a style='font-size: 116.30%' href='http://martin-thoma.com/tag/algorithms/' title='7 topics'>algorithms</a>
<a style='font-size: 270.00%' href='http://martin-thoma.com/tag/mathematics/' title='50 topics'>mathematics</a>
<a style='font-size: 144.19%' href='http://martin-thoma.com/tag/lecture-notes/' title='10 topics'>lecture-notes</a>
<a style='font-size: 190.13%' href='http://martin-thoma.com/tag/linear algebra/' title='18 topics'>Linear algebra</a>
<a style='font-size: 104.25%' href='http://martin-thoma.com/tag/php/' title='6 topics'>PHP</a>
<a style='font-size: 126.74%' href='http://martin-thoma.com/tag/it-security/' title='8 topics'>IT-Security</a>
<a style='font-size: 194.36%' href='http://martin-thoma.com/tag/klausur/' title='19 topics'>Klausur</a>
<a style='font-size: 90.00%' href='http://martin-thoma.com/tag/os/' title='5 topics'>OS</a>
<a style='font-size: 90.00%' href='http://martin-thoma.com/tag/operating systems/' title='5 topics'>Operating Systems</a>
<a style='font-size: 239.85%' href='http://martin-thoma.com/tag/java/' title='34 topics'>Java</a>
<a style='font-size: 209.30%' href='http://martin-thoma.com/tag/latex/' title='23 topics'>LaTeX</a>
<a style='font-size: 104.25%' href='http://martin-thoma.com/tag/analysis/' title='6 topics'>analysis</a>
<a style='font-size: 202.18%' href='http://martin-thoma.com/tag/puzzle/' title='21 topics'>puzzle</a>
<a style='font-size: 126.74%' href='http://martin-thoma.com/tag/digitaltechnik/' title='8 topics'>Digitaltechnik</a>
<a style='font-size: 158.44%' href='http://martin-thoma.com/tag/kit/' title='12 topics'>KIT</a>
<a style='font-size: 135.95%' href='http://martin-thoma.com/tag/project euler/' title='9 topics'>Project Euler</a>
<a style='font-size: 126.74%' href='http://martin-thoma.com/tag/swt i/' title='8 topics'>SWT I</a>
<a style='font-size: 170.49%' href='http://martin-thoma.com/tag/funny/' title='14 topics'>funny</a>
<a style='font-size: 90.00%' href='http://martin-thoma.com/tag/wolfram|alpha/' title='5 topics'>Wolfram|Alpha</a>
<a style='font-size: 104.25%' href='http://martin-thoma.com/tag/computer science/' title='6 topics'>Computer science</a>
<a style='font-size: 126.74%' href='http://martin-thoma.com/tag/vimeo/' title='8 topics'>Vimeo</a>
<a style='font-size: 144.19%' href='http://martin-thoma.com/tag/clip/' title='10 topics'>Clip</a>
<a style='font-size: 104.25%' href='http://martin-thoma.com/tag/cpp/' title='6 topics'>CPP</a>
<a style='font-size: 151.64%' href='http://martin-thoma.com/tag/youtube/' title='11 topics'>YouTube</a>
<a style='font-size: 126.74%' href='http://martin-thoma.com/tag/matrix/' title='8 topics'>Matrix</a>
<a style='font-size: 135.95%' href='http://martin-thoma.com/tag/web development/' title='9 topics'>Web Development</a>
<a style='font-size: 90.00%' href='http://martin-thoma.com/tag/javascript/' title='5 topics'>JavaScript</a>
<a style='font-size: 90.00%' href='http://martin-thoma.com/tag/kogsys/' title='5 topics'>KogSys</a>
<a style='font-size: 116.30%' href='http://martin-thoma.com/tag/challenge/' title='7 topics'>Challenge</a>
<a style='font-size: 104.25%' href='http://martin-thoma.com/tag/algebra/' title='6 topics'>Algebra</a>
<a style='font-size: 90.00%' href='http://martin-thoma.com/tag/geometry/' title='5 topics'>Geometry</a>
<a style='font-size: 104.25%' href='http://martin-thoma.com/tag/flashgames/' title='6 topics'>Flashgames</a>
<a style='font-size: 175.88%' href='http://martin-thoma.com/tag/learning/' title='15 topics'>learning</a>
<a style='font-size: 151.64%' href='http://martin-thoma.com/tag/video/' title='11 topics'>Video</a>
<a style='font-size: 90.00%' href='http://martin-thoma.com/tag/assembly language/' title='5 topics'>Assembly language</a>
<a style='font-size: 104.25%' href='http://martin-thoma.com/tag/tikz/' title='6 topics'>Tikz</a>
<a style='font-size: 90.00%' href='http://martin-thoma.com/tag/command line/' title='5 topics'>Command Line</a>
<a style='font-size: 104.25%' href='http://martin-thoma.com/tag/bash/' title='6 topics'>Bash</a>
</div>
</li>		</ul>
	</div>
</div>
		</div><!--/container-->
			<div id="footer">
				<a href="http://martin-thoma.com"><strong>Martin Thoma</strong></a> -  A blog about Code, the Web and Cyberculture. <br />
                <div class="footer-credits">
                    <a href="http://flexithemes.com/themes/modern-style/">Modern Style</a> theme by <a href="http://flexithemes.com/">FlexiThemes</a>
                </div>
			</div><!--/footer-->
            
	</div><!--/wrapper-->
<!-- type: footer -->
<!-- MathJax -->
<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
<script type="text/javascript">
<!--
MathJax.Hub.Config({
  jax: ["input/TeX", "output/HTML-CSS"],
  tex2jax: {
    inlineMath: [['$','$'], ['\\(','\\)']],
    displayMath: [ ['$$', '$$']],
    skipTags: ['script', 'noscript', 'style', 'textarea', 'pre', 'code'],
    processEscapes: true
  }
});

MathJax.Hub.Queue(function() {
    var all = MathJax.Hub.getAllJax(), i;
    for(i=0; i < all.length; i += 1) {
        all[i].SourceElement().parentNode.className += ' has-jax';
    }
});
// -->
</script>
<!-- TOC Plus -->
<script type='text/javascript'>
/* <![CDATA[ */
var tocplus = {"visibility_show":"show","visibility_hide":"hide","width":"275px"};
/* ]]> */
</script>
<script type='text/javascript' src="http://martin-thoma.com/js/tocplus-front.js"></script>

</body>
</html>

