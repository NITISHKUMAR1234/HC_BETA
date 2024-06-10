<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet 
  version="1.0"
  xmlns:sm="http://www.sitemaps.org/schemas/sitemap/0.9"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0"
  xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
  xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"
  xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
  xmlns:fo="http://www.w3.org/1999/XSL/Format"
  xmlns="http://www.w3.org/1999/xhtml">
 
  <xsl:output method="html" indent="yes" encoding="UTF-8"/>

  <xsl:template match="/">
<html>
<head>
<title>
<xsl:if test="sm:urlset/sm:url/mobile:mobile">Mobile </xsl:if>
<xsl:if test="sm:urlset/sm:url/image:image">Images </xsl:if>
<xsl:if test="sm:urlset/sm:url/news:news">News </xsl:if>
<xsl:if test="sm:urlset/sm:url/video:video">Video </xsl:if>
XML Sitemap
<xsl:if test="sm:sitemapindex"> Index</xsl:if>
</title>
<style type="text/css">
body {
	background-color: #c9bdaf; /* Background color #c9bdaf */
	font: normal 100%  "Trebuchet MS", "Helvetica", sans-serif;
	margin:0;
	text-align:center;
	color: #404040; /* Text color */
}
#cont{
	margin:auto;
	width:100%;
	text-align:left;
}
a:link,a:visited {
	color: #004752; /* Link color */
	text-decoration: none;
}
a:hover {
	color: #00d2f3; /* Link hover color */
}
h1 {
	background-color:#000;
	padding:8px;
	color: #ffffff; /* Header text color */
	text-align:left;
	font-size:32px;
	margin:0px;
}
h3 {
	font-size:12px;
	background-color: #e1deda; /* Subheader background color */
	margin:0px;
	padding:6px;
}
h3 a {
	float:right;
	font-weight:normal;
	display:block;
}
th {
	text-align:center;
	background-color: #000000;
	color:#fff;
	padding:4px;
	font-weight:normal;
	font-size:14px;
}
td{
	font-size:14px;
	padding:5px;
	text-align:left;
	color: #404040; /* Table data text color */
}
tr {
    background: #fff;
    padding: 5px 0;
    border-top: 1px solid #ddd;
}
tr:nth-child(odd) {
    background: #f9f9f9;
}
.lastmod,.changefreq,.priority {
    margin: 0px 2px;
    padding: 0px 2px;
    font-style: italic;
    border: 1px solid #e8e6e65e;
    text-align: center;
}
td.lastmod {
    background: #fff9f2;
    width: 179px;
}
td.changefreq {
    background: #f5fff2;
}
td.priority {
    background: #f2fffd;
}
.url2 {
	text-align:right;
}
#footer {
    background-color: #000000; /* Footer background color */
    padding: 10px;
    text-align: center;
    color: #ffffff; /* Footer text color */
}
</style>
</head>
<body><div id="cont">
<h1>
<xsl:if test="sm:urlset/sm:url/mobile:mobile">Mobile </xsl:if>
<xsl:if test="sm:urlset/sm:url/image:image">Images </xsl:if>
<xsl:if test="sm:urlset/sm:url/news:news">News </xsl:if>
<xsl:if test="sm:urlset/sm:url/video:video">Video </xsl:if>
Sitemap<xsl:if test="sm:sitemapindex"> Index</xsl:if></h1>
<h3>
<xsl:choose>
<xsl:when  test="sm:sitemapindex"> 
Total sitemap files listed in this index: <xsl:value-of select="count(sm:sitemapindex/sm:sitemap)"/>
</xsl:when>
<xsl:otherwise> 
Total URL: <xsl:value-of select="count(sm:urlset/sm:url)"/>
</xsl:otherwise>
</xsl:choose>
</h3>

<xsl:apply-templates />

<div id="footer">
&#169; 2023 Sitemap
</div>
</div>

</body>
</html>
  </xsl:template>
 
 
  <xsl:template match="sm:sitemapindex">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<th></th>
<th>URL</th>
<xsl:if test="sm:sitemap/sm:lastmod"><th style="width: 170px">Lastmod</th></xsl:if>
</tr>
<xsl:for-each select="sm:sitemap">
<tr> 
<xsl:variable name="loc"><xsl:value-of select="sm:loc"/></xsl:variable>
<xsl:variable name="pno"><xsl:value-of select="position()"/></xsl:variable>
<td><xsl:value-of select="$pno"/></td>
<td><a href="{$loc}"><xsl:value-of select="sm:loc"/></a></td>
<xsl:apply-templates/> 
</tr>
</xsl:for-each>
</table>
  </xsl:template>
 
  <xsl:template match="sm:urlset">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<th></th>
<th>URL</th>
<xsl:if test="sm:url/sm:lastmod"><th style="width: 170px">Lastmod</th></xsl:if>
<xsl:if test="sm:url/sm:changefreq"><th>Changefreq</th></xsl:if>
<xsl:if test="sm:url/sm:priority"><th>Priority</th></xsl:if>
</tr>
<xsl:for-each select="sm:url">
<tr> 
<xsl:variable name="loc"><xsl:value-of select="sm:loc"/></xsl:variable>
<xsl:variable name="pno"><xsl:value-of select="position()"/></xsl:variable>
<td><xsl:value-of select="$pno"/></td>
<td><a href="{$loc}"><xsl:value-of select="sm:loc"/></a></td>
<xsl:apply-templates select="sm:*"/> 
</tr>
<xsl:apply-templates select="image:*"/> 
<xsl:apply-templates select="video:*"/> 
</xsl:for-each>
</table>
  </xsl:template>

<xsl:template match="sm:loc|image:loc|image:caption|video:*">
</xsl:template>


<xsl:template match="sm:lastmod"><td class="lastmod"><xsl:apply-templates/></td></xsl:template>
<xsl:template match="sm:changefreq"><td class="changefreq"><xsl:apply-templates/></td></xsl:template>
<xsl:template match="sm:priority"><td class="priority"><xsl:apply-templates/></td></xsl:template>


  <xsl:template match="image:image">
<tr> 
<xsl:variable name="loc"><xsl:value-of select="image:loc"/></xsl:variable>
<td></td>
<td class="url2"><a href="{$loc}"><xsl:value-of select="image:loc"/></a></td>
<td colspan="5"><div style="width:400px"><xsl:value-of select="image:caption"/></div></td>
<xsl:apply-templates/> 
</tr>
  </xsl:template>
  <xsl:template match="video:video">
<tr> 
<xsl:variable name="loc"><xsl:choose><xsl:when test="video:player_loc != ''"><xsl:value-of select="video:player_loc"/></xsl:when><xsl:otherwise><xsl:value-of select="video:content_loc"/></xsl:otherwise></xsl:choose></xsl:variable>
<xsl:variable name="thumb"><xsl:value-of select="video:thumbnail_loc"/></xsl:variable>
<td></td>
<td class="url2"><a href="{$loc}"><xsl:choose><xsl:when test="video:player_loc != ''"><xsl:value-of select="video:player_loc"/></xsl:when><xsl:otherwise><xsl:value-of select="video:content_loc"/></xsl:otherwise></xsl:choose></a></td>
<td colspan="5"><div style="width:400px"><xsl:value-of select="video:title"/></div>
<xsl:if test="video:thumbnail_loc != ''"><img src="{$thumb}" alt="" /></xsl:if></td>
<xsl:apply-templates/> 
</tr>
  </xsl:template>

</xsl:stylesheet>
