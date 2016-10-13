<?php
$crawledURLS = array();
$siteMap = array();
function crawlSite($urls)
{
	global $crawledURLS;
	global $crawlingSite;
	global $siteMap;
	foreach($urls as $url)
	{
		$pageContent=file_get_contents($url);
		$pageContent = strip_tags($pageContent,"<a>");
		$parsedURLS = preg_split("/<\/a>/",$pageContent);
		$fp = fopen("urllist.log","a");
		foreach ( $parsedURLS as $k=>$u )
		{
			$u = preg_replace("/.*<a\s+href=\"/sm","",$u);
			$u = preg_replace("/\".*/","",$u);
			$urlNew = (strstr($u,"ajsquare.")==false?"http://ajsquare.com/":"").$u;
			//fwrite($fp,$urlNew."\r\n");
			$siteMap[$url][] = $urlNew;
		}
		fclose($fp);
		if(!in_array($url,$crawledURLS) && strstr($url,$crawlingSite))
			{
				$fp = fopen("urllist.log","a");
				//fwrite($fp,print_r($crawledURLS,true));
				fwrite($fp,"crawling url :".$url."\r\n");
				fclose($fp);
				$crawledURLS[] = $url;
				crawlSite($siteMap[$url]);
			}
	}
	return $siteMap;
}


$crawlingSite = "http://ajsquare.com/";
$urls= array($crawlingSite);
$siteMap = crawlSite($urls);