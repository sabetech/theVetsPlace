<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Fine Uploader Demo</title>
    <link href="client/fineuploader.css" rel="stylesheet">
  </head>
  <body>
    <div id="fine-uploader"></div>
	
	<script src="http://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript"></script>

    <script src="client/js/jquery-plugin.js" type="text/javascript"></script>
    <script>
      function createUploader() {
        var uploader = new qq.FineUploader({
          element: document.getElementById('fine-uploader'),
          request: {
            endpoint: 'server/handleUploads'
          }
        });
      }
      
      window.onload = createUploader;
    </script>
  </body>
</html>