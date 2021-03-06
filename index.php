<?php
$response = array(
    'data' => null,
    'error' => null,
);
$isResponse = false;
$isError = false;
$isSuccessResponse = false;
$providerName = null;
$url = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isResponse = true;
    require_once __DIR__ . '/YoutubeDownloader.php';
    try {
        if (!isset($_POST['url']) || !trim($_POST['url'])) {
            throw new VideoDownloaderException("Url does not set");
        }
        $url = trim($_POST['url']);
        $yd = new YoutubeDownloader($url);
        $fullInfo = $yd->getFullInfo();
        $videoId = $fullInfo['video_id'];
        $response['data'] = array(
            'baseInfo' => $yd->getBaseInfo(),
            'downloadInfo' => $yd->getDownloadsInfo(),
        );
        $isSuccessResponse = true;
    } catch (Exception $e) {
        $isError = true;
        header('Bad request', true, 400);
        $response['error'] = $e->getMessage();
    }
}
?>
<!doctype html>
<html amp="amp">
<head>
<b:include name='openGraphMetaData'/>
<script type='text/javascript'>(function(){var html5=("abbr,article,aside,audio,canvas,svg,datalist,details,"+"figure,footer,nav,header,hgroup,mark,menu,meter,output,"+"progress,section,time,video").split(',');for(var i=0;i<html5.length;i++){document.createElement(html5[i])}try{document.execCommand('BackgroundImageCache',false,true)}catch(e){}})()</script>
<meta content='950 ' http-equiv='refresh'/>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Simple Youtube Video Downloader</title>
</head>
<body>


	
	<div id="main">
	  <boxbody>
	  <center>
	  <form action="" method="post" id="download-form">
	  <div class="row">
	  <div class="col-md-10">
	  <div class="form-group <?= $isError ? 'has-error' : '' ?>">
	  <input id="video-url" title="Video url" type="text" name="url" placeholder="Video url"
	  class="form-control" value="<?= htmlspecialchars($url) ?>"/>
	  </div>
	  </div>
	  <div class="col-md-2">
	  <div class="form-group">
	  <button class="waves-effect btn btn-primary">Get download links</button>
	  </div>
	  </div>
	  </div>
	  </form>
	  </center>
	  <div class="alert alert-danger" role="alert" id="error-block" style="display: <?= $isError ? 'block' : 'none' ?>">
	  <?= $isError ? $response['error'] : '' ?>
	  </div>
	  
	  <?php if ($isSuccessResponse): ?>
	  <?php
	  $baseInfo = $response['data']['baseInfo'];
	  $downloadInfo = $response['data']['downloadInfo'];
	  ?>
	  <?php endif; ?>
	  
	  <h3 id="video-name">
	  <?php if ($isSuccessResponse): ?>
	  <?= htmlspecialchars($baseInfo['name']) ?>
	  <?php endif; ?>
	  </h3>
	  
	  <div class="row">
	  <div class="col-md-6" style="display: <?= $isSuccessResponse ? 'block' : 'none' ?>">
	  <div id="video-preview">
	  <?php if ($isSuccessResponse): ?>
	  <img src="<?= $baseInfo['previewUrl'] ?>" alt="Video preview"
	  class="img-responsive img-thumbnail img-rounded">
	  <?php endif; ?>
	  </div>
	  <pre id="video-description">
	  <?php if ($isSuccessResponse): ?>
	  <?= htmlspecialchars($baseInfo['description']) ?>
	  <?php endif; ?>
	  </pre>
	  </div>
	  
	  </div>
	  </div>
	  </boxbody>

	</div>
	
	<div id="footer" >
<div class="col-md-6">
	  <table id="download-list" class="table">
	  <thead <?= !$isSuccessResponse ? 'style="display: none"' : '' ?>>
	  <tr>
	  <th>Type</th>
	  <th>Size</th>
	  <th>Download link</th>
	  </tr>
	  </thead>
	  <tbody>
	  <?php if ($isSuccessResponse): ?>
	  <?php foreach ($downloadInfo AS $downloadInfoItem): ?>
	  <tr>
	  <td><?= $downloadInfoItem['fileType'] ?></td>
	  <td><?= $downloadInfoItem['fileSizeHuman'] ?></td>
	  <td>
	  <?php
	  $downloadUrl = 'download.php?id=' . $videoId . '&itag=' . $downloadInfoItem['youtubeItag'];
	  ?>
	  <a
	  href="<?= $downloadUrl ?>"
	  target="_blank"
	  class="waves-effect btn btn-success"
	  >
	  <span class="glyphicon glyphicon-circle-arrow-down"></span>
	  Download
	  </a>
	  </td>
	  </tr>
	  <?php endforeach; ?>
	  <?php endif; ?>
	  </tbody>
	  </table>
	  </div>
	</div>


</body>
<link rel="stylesheet" href="css/styles.css">
<link rel="stylesheet" href="https://cdn.statically.io/gh/AddWeb/Material-Design/6a600197/style.css">
<link rel="stylesheet" href="https://cdn.statically.io/gh/AddWeb/Button/d989ac97/style.css">
<script src="https://cdn.statically.io/gh/AddWeb/Material-Design/6a600197/script.js"></script>
<script src="js/‏script.js"></script>
</html>