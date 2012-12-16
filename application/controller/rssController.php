<?php

class rssController extends appController
{
	function __construct()
	{
		parent::__construct();
		$this->view->view();
	}
	
	function view()
	{
		header("Content-Type: application/rss+xml; encoding=utf-8");
		$rssFeed = '<?xml version="1.0" encoding="utf-8" ?>'."\n";
		$rssFeed .= '<rss version="2.0">'."\n";
		$rssFeed .= '<channel>'."\n";
		$rssFeed .= '<title>ار اس اس</title>'."\n";
		$rssFeed .= '<link>http://www.site.com</link>'."\n";
		$rssFeed .= '<description>description</description>'."\n";
		$rssFeed .= '<language>fa</language>'."\n\n";
		
		$posts=$this->rss->select('blog_post')->order()->limit(0,20)->fetchAll();
		
		foreach($posts as $post)
		{
			$rssFeed .= "\t".'<item>'."\n";
			$rssFeed .= "\t".'<title>' . $post['post_title'] . '</title>'."\n";
			$rssFeed .= "\t".'<description>' . htmlentities($post['post_short_content']) . '</description>'."\n";
			$rssFeed .= "\t".'<link>' . URL.'post/view/'.$post['id'] . '</link>'."\n";
			$rssFeed .= "\t".'<pubDate>' . $post['post_date'] . '</pubDate>'."\n";
			$rssFeed .= "\t".'</item>'."\n"."\n";				
		}
		
		$rssFeed .= '</channel>'."\n";
    	$rssFeed .= '</rss>'."\n";
		echo $rssFeed;
		die('');	
	}
}
?>