$ ->
    errorHandler = (event, id, fileName, reason, xhr) ->
        qq.log "id: #{id}, fileName: #{fileName}, reason: #{reason}"

    $('#basicUploadSuccessExample').fineUploaderS3(
        debug: true
        request:
            endpoint: "http://fineuploadertest.s3.amazonaws.com"
            accessKey: 'AKIAJEQ4NDFBCZAMWGUQ'
        signature:
            endpoint: '/upload/s3/signature'
        uploadSuccess:
            endpoint: '/upload/s3/success'
        iframeSupport:
            localBlankPagePath: 'success.html'
        chunking:
            enabled: true
        resume:
            enabled: true
        retry:
            enableAuto: true
            showButton: true
        deleteFile:
            enabled: true
            endpoint: '/upload/s3/files'
            forceConfirm: true
            params:
                foo: "bar"
        failedUploadTextDisplay:
            mode: 'custom'
        display:
            fileSizeOnSubmit: true
        paste:
            targetElement: $(document)
    )
        .on('error', errorHandler)
        .on "upload", (event, id, filename) ->
            $(this).fineUploader 'setParams', {"hey": "hi ɛ $ hmm \\ hi", "ho": "foobar"}, id
        .on 'statusChange', (event, id, oldS, newS) ->
            qq.log "id: #{id} #{newS}"

    $("#basicUploadSuccessExample > div > div > input[name='qqfile']").hover (event) ->
        event.preventDefault()

    $("#basicUploadSuccessExample > div > .qq-upload-button").hover (event) ->
        event.preventDefault()


    $('#manualUploadModeExample').fineUploader(
        autoUpload: false
        debug: true
        uploadButtonText: "Select Files"
        request:
            endpoint: "/upload/receiver"
        deleteFile:
            enabled: true
            endpoint: '/upload/receiver'
            forceConfirm: true
            params:
                foo: "bar"
        display:
            fileSizeOnSubmit: true
    )
        .on 'error', errorHandler

    $('#triggerUpload').click ->
        $('#manualUploadModeExample').fineUploader "uploadStoredFiles"


    $('#basicUploadFailureExample').fineUploader(
        request:
            endpoint: "/upload/receiver"
            params: "generateError": "true"
        debug: true
        failedUploadTextDisplay:
            mode: 'custom'
            maxChars: 5
        retry:
            enableAuto: true
            showButton: true
    )
        .on 'error', errorHandler


    $('#uploadWithVariousOptionsExample').fineUploader(
        multiple: false
        request:
            endpoint: "/upload/receiver"
        debug: true
        validation:
            allowedExtensions: ['jpeg', 'jpg', 'txt']
            sizeLimit: 50000
            minSizeLimit: 2000
        text:
            uploadButton: "Click Or Drop"
        display:
            fileSizeOnSubmit: true
    )
        .on 'error', errorHandler


    $('#fubExample').fineUploader(
        uploaderType: 'basic'
        multiple: false
        debug: true
        autoUpload: false
        button: $("#fubUploadButton")
        request:
            endpoint: "/upload/receiver"
    )
        .on 'error', errorHandler
