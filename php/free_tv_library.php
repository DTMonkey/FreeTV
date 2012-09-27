<?php
	include_once('simple_html_dom.php');
		
	class MediaParser {	
		const BASE_SHOW_URL = "http://www.free-tv-video-online.me/internet/%s/season_%d.html";
	
		public function getShow($name, $season) {
			$name 		=	str_replace(' ', '_', strtolower($name));
		
			$url 		= 	sprintf(self::BASE_SHOW_URL, $name, $season);
			
			$html	 	= 	file_get_html($url);
			
			$header		=	array();	 
			$info		= 	array();
			$links		=	array();
			
			$episodes 	= 	array();
					
			foreach($html->find('tr[class=3]') as $element) 
				$header[] 	=	$element->plaintext;
				
			foreach($html->find('tr[class=none]') as $element) 
				$info[] 	=	$element->plaintext;
				
			foreach($html->find('a[onclick]') as $element) 
				$links[] 	=	$element;
				
			for($i=0; $i < count($header); $i++) {
				$episodes[] = new Episode($i+1, $header[$i], $info[$i]);
			
				foreach($links as $link) {
					if(strpos($link, sprintf("Episode %d</div>", $i+1))) {
						$episodes[$i]->addLink($link->href);
					}
				}
			}
			
			return $episodes;
		}
			
		public function printInfo() {
			foreach($episodes as $episode) {
				echo $episode->toString();
		
				foreach($episode->mLinks as $link) 
					echo '<a href="' . $link . '"> Video </a>';
							
				echo "</br></br>";
			}
		}
	}
	
	class Episode {
		var $mNum, $mTitle, $mAirDate, $mInfo, $mLinks;
		
		public function __construct($num, $title, $info) {
			$pos = strpos($title, '/');
		
			$this->mNum = $num;
			$this->mTitle = substr($title, 0, $pos);
			$this->mAirDate = substr($title, $pos + 1);
			$this->mInfo =  utf8_decode($info);
			
			$this->mLinks = array();
		}
		
		public function addLink($link) {
			$key = substr($link, strpos($link, "?id=")+4);
		
			if(strpos($link, "putlocker"))
				$link = sprintf("http://www.putlocker.com/embed/%s", $key);
			else if(strpos($link, "sockshare"))
				$link = sprintf("http://www.sockshare.com/embed/%s", $key);
			else if(strpos($link, "gorillavid"))
				$link = sprintf("http://gorillavid.in/vidembed-%s.avi", $key);
			
			$this->mLinks[] = $link;
		}
		
		public function toString() {
			return $this->mTitle . "</br>" . $this->mInfo . "</br>";
		}
	}
?>