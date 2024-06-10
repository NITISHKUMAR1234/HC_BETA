<?php
require_once('../include/config.php');
// require_once('../assets/functions.php');
// sitemap settings from database 
define('SITEURL','onespect.in.net/Calendar/beta');
$sitemapQ = "SELECT * FROM tb_sitemap WHERE id=1";
$sitemapR = @mysqli_query($conn,$sitemapQ);
if(@mysqli_num_rows($sitemapR)>0)
{
    $sitemap = @mysqli_fetch_object($sitemapR);
    $xmlfile  = "./sitemap.xml";
    $base_url = strtolower('https://'.SITEURL);
    date_default_timezone_set($sitemap->timezone);
    $ctime = date('c', time());
    $data[] = array('url'=>$base_url.'/', 'lastmod'=>$ctime, 'changefreq'=>$sitemap->changefreq, 'priority'=>'1.00');
    // for posts 
    $postS = "SELECT fd_slno,fd_sub_menu FROM tb_submenu WHERE fd_status=0 ORDER BY fd_slno DESC";
    $postR = @mysqli_query($conn,$postS);
    if(@mysqli_num_rows($postR)>0)
    {
        while($posts = @mysqli_fetch_object($postR))
        {
        $postUrl = $base_url.'/post/'.$posts->fd_slno.'/'.slugify($posts->fd_sub_menu);
        $data[] = array(
            'url'=>$postUrl,
            'lastmod'=>$ctime,
            'changefreq'=>$sitemap->changefreq,
            'priority'=>'0.80'
            );
        } 
    }
    // for histories date
    $subHistoryQ = "SELECT DISTINCT tb_calender_date FROM tb_history";
    $subHistoryR = @mysqli_query($conn,$subHistoryQ);
    if(@mysqli_num_rows($subHistoryR)>0)
    {
        while($subHistory = @mysqli_fetch_object($subHistoryR))
        {
            $category_id = $subHistory->tb_calender_date;
            $subHistoryURL = "https://onespect.in.net/Calendar/beta/index.php?histroy_feed&date=".$subHistory->tb_calender_date;

            $data[] = array(
                'url'=>$subHistoryURL,
                'lastmod'=>$ctime,
                'changefreq'=>$sitemap->changefreq,
                'priority'=>'0.80'
            );

            // echo $subHistoryURL;
        } 

    }
    // for extra links
    $data[] = array(
        'url'=>$base_url.'/login',
        'lastmod'=>$ctime,
        'changefreq'=>$sitemap->changefreq,
        'priority'=>'0.64'
    );
    $data[] = array(
        'url'=>$base_url.'/terms.php',
        'lastmod'=>$ctime,
        'changefreq'=>$sitemap->changefreq,
        'priority'=>'0.64'
    );
    $data[] = array(
        'url'=>$base_url.'/privecy.php',
        'lastmod'=>$ctime,
        'changefreq'=>$sitemap->changefreq,
        'priority'=>'0.64'
    );
    
    $totalXml=ceil(count($data)/$sitemap->limits);
    $arrayXml=array_fill(0,$totalXml, 'xml');

    foreach(array_keys($arrayXml) as $c):
        $datalist[$c]  ='<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $datalist[$c] .='<?xml-stylesheet type="text/xsl" href="/css/sitemap.xsl"?>'.PHP_EOL;
        $datalist[$c] .='<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'.PHP_EOL.PHP_EOL;
        
    $total = $c<1 ? (($c+1)*$sitemap->limits)-1 : ($c+1)*$sitemap->limits;
    $page  = $c==0 ? $c : ($c>0 ? ($c*$sitemap->limits)+1 : ($c*$sitemap->limits)-1);
    for ($i = $page; $i <= $total; $i++){
        if(isset($data[$i]['url'])){
        $datalist[$c] .= "<url>".PHP_EOL;
        $datalist[$c] .= '<loc>'.$data[$i]['url'].'</loc>'.PHP_EOL;
        $datalist[$c] .= $sitemap->lastmod    ? '<lastmod>'.($sitemap->datemode ? $ctime : $data[$i]['lastmod']).'</lastmod>'.PHP_EOL : '';
        $datalist[$c] .= $sitemap->changefreq ? '<changefreq>' .$data[$i]['changefreq']. '</changefreq>'.PHP_EOL : '';
        $datalist[$c] .= $sitemap->priority   ? '<priority>'   .$data[$i]['priority'].   '</priority>'.PHP_EOL : '';
        $datalist[$c] .= "</url>".PHP_EOL;
        }
        if(count($data)==$i) break;
    }
        $datalist[$c] .= '</urlset>';
    if($totalXml == 1)
    $writeData = $datalist[$c];
    endforeach;

    if($totalXml > 1){
        $datafile  = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $datafile .= '<?xml-stylesheet type="text/xsl" href="/css/sitemap.xsl"?>'.PHP_EOL;
        $datafile .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
        foreach(array_keys($arrayXml) as $k):
        $xmlfiles='sitemap'.($k+1).'.xml';
        $wFile = fopen($xmlfiles, "w");
        fwrite($wFile, $datalist[$k]);
        fclose($wFile);
        $datafile .= '<sitemap>'.PHP_EOL;
        $datafile .= '<loc>'.SITEURL.'/'.$xmlfiles.'</loc>'.PHP_EOL;
        $datafile .= $sitemap->lastmod ? '<lastmod>'.$ctime.'</lastmod>'.PHP_EOL : '';
        $datafile .= '</sitemap>'.PHP_EOL;
        endforeach;
        $datafile .= '</sitemapindex>';
    
    $writeData = $datafile;
    }

    $wFile = fopen($xmlfile, "w") or die("Unable to open file!");
    if (fwrite($wFile, $writeData)) {
        $xmlfile = 'sitemap.xml';
        $sitemapURL = 'https://'.SITEURL.'/'.$xmlfile;
        @file_get_contents('https://www.google.com/webmasters/tools/ping?sitemap='.$sitemapURL);
        echo 'sitemap created successfully <a href="'.$sitemapURL.'">View Sitemap</a>';
    }
    fclose($wFile);
}
?>