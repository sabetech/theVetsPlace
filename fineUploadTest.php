<html>
  <head>
      <link href="fineuploader-3.8.0.css" rel="stylesheet" type="text/css"/>
  </head>
  <body>

    <!-- The element where Fine Uploader will exist. -->
    <div id="fine-uploader">
    </div>

    <!-- jQuery version 1.10.x (if you are using the jQuery plugin -->
    <script src="http://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript"></script>

    <!-- Fine Uploader-jQuery -->
    <script src="jquery.fineuploader-3.8.0.min.js" type="text/javascript"></script>

    <script>
    // Wait until the DOM is 'ready'
    $(document).ready(function () {
        $("#fine-uploader").fineUploader({
            debug: true,
            request: {
                endpoint: '/uploads'
            },
            deleteFile: {
                enabled: true,
                endpoint: '/uploads'
            },
            retry: {
               enableAuto: true
            }
        });
    });
    </script>

  </body>
</html>