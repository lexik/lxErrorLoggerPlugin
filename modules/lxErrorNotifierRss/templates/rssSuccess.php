<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
	<channel>
		<title>lxErrorLoggerPlugin RSS</title>
		<description>List of latest errors</description>
		<link></link>
		<?php foreach ($datas as $error): ?>
    <item>
      <title>Error #<?php echo $error['id'].' '.$error['environment'].' '.$error['type'] ?></title>
      <pubDate><?php echo date(DATE_RSS, strtotime($error['created_at'])) ?></pubDate>
      <description><![CDATA[<?php echo lxErrorRenderer::toHtml($error) ?>]]></description>
      <guid><?php echo $error['id'] ?></guid>
      <link></link>
    </item>
    <?php endforeach; ?>
	</channel>
</rss>