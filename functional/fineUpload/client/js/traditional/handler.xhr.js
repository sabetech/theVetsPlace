/*globals qq, File, XMLHttpRequest, FormData, Blob*/
qq.UploadHandlerXhr = function(options, uploadCompleteCallback, onUuidChanged, logCallback) {
    "use strict";
    
    var uploadComplete = uploadCompleteCallback,
        log = logCallback,
        fileState = [],
        cookieItemDelimiter = "|",
        chunkFiles = options.chunking.enabled && qq.supportedFeatures.chunking,
        resumeEnabled = options.resume.enabled && chunkFiles && qq.supportedFeatures.resume,
        resumeId = getResumeId(),
        multipart = options.forceMultipart || options.paramsInBody,
        internalApi = {},
        publicApi;


     function addChunkingSpecificParams(id, params, chunkData) {
        var size = publicApi.getSize(id),
            name = publicApi.getName(id);

        params[options.chunking.paramNames.partIndex] = chunkData.part;
        params[options.chunking.paramNames.partByteOffset] = chunkData.start;
        params[options.chunking.paramNames.chunkSize] = chunkData.size;
        params[options.chunking.paramNames.totalParts] = chunkData.count;
        params[options.totalFileSizeParam] = size;

        /**
         * When a Blob is sent in a multipart request, the filename value in the content-disposition header is either "blob"
         * or an empty string.  So, we will need to include the actual file name as a param in this case.
         */
        if (multipart) {
            params[options.filenameParam] = name;
        }
    }

    function addResumeSpecificParams(params) {
        params[options.resume.paramNames.resuming] = true;
    }

    function getChunk(fileOrBlob, startByte, endByte) {
        if (fileOrBlob.slice) {
            return fileOrBlob.slice(startByte, endByte);
        }
        else if (fileOrBlob.mozSlice) {
            return fileOrBlob.mozSlice(startByte, endByte);
        }
        else if (fileOrBlob.webkitSlice) {
            return fileOrBlob.webkitSlice(startByte, endByte);
        }
    }

    function setParamsAndGetEntityToSend(params, xhr, fileOrBlob, id) {
        var formData = new FormData(),
            method = options.demoMode ? "GET" : "POST",
            endpoint = options.endpointStore.getEndpoint(id),
            url = endpoint,
            name = publicApi.getName(id),
            size = publicApi.getSize(id),
            blobData = fileState[id].blobData,
            newName = fileState[id].newName;

        params[options.uuidParam] = fileState[id].uuid;

        if (multipart) {
            params[options.totalFileSizeParam] = size;

            if (blobData) {
                /**
                 * When a Blob is sent in a multipart request, the filename value in the content-disposition header is either "blob"
                 * or an empty string.  So, we will need to include the actual file name as a param in this case.
                 */
                params[options.filenameParam] = blobData.name;
            }
        }

        if (newName !== undefined) {
            params[options.filenameParam] = newName;
        }

        //build query string
        if (!options.paramsInBody) {
            if (!multipart) {
                params[options.inputName] = newName || name;
            }
            url = qq.obj2url(params, endpoint);
        }

        xhr.open(method, url, true);

        if (options.cors.expected && options.cors.sendCredentials) {
            xhr.withCredentials = true;
        }

        if (multipart) {
            if (options.paramsInBody) {
                qq.obj2FormData(params, formData);
            }

            formData.append(options.inputName, fileOrBlob);
            return formData;
        }

        return fileOrBlob;
    }

    function setHeaders(id, xhr) {
        var extraHeaders = options.customHeaders,
            fileOrBlob = fileState[id].file || fileState[id].blobData.blob;

        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.setRequestHeader("Cache-Control", "no-cache");

        if (!multipart) {
            xhr.setRequestHeader("Content-Type", "application/octet-stream");
            //NOTE: return mime type in xhr works on chrome 16.0.9 firefox 11.0a2
            xhr.setRequestHeader("X-Mime-Type", fileOrBlob.type);
        }

        qq.each(extraHeaders, function(name, val) {
            xhr.setRequestHeader(name, val);
        });
    }

    function handleCompletedItem(id, response, xhr) {
        var name = publicApi.getName(id),
            size = publicApi.getSize(id);

        fileState[id].attemptingResume = false;

        options.onProgress(id, name, size, size);
        options.onComplete(id, name, response, xhr);

        if (fileState[id]) {
            delete fileState[id].xhr;
        }

        uploadComplete(id);
    }

    function uploadNextChunk(id) {
        var chunkIdx = fileState[id].remainingChunkIdxs[0],
            chunkData = internalApi.getChunkData(id, chunkIdx),
            xhr = internalApi.createXhr(id),
            size = publicApi.getSize(id),
            name = publicApi.getName(id),
            toSend, params;

        if (fileState[id].loaded === undefined) {
            fileState[id].loaded = 0;
        }

        if (resumeEnabled && fileState[id].file) {
            persistChunkData(id, chunkData);
        }

        xhr.onreadystatechange = getReadyStateChangeHandler(id, xhr);

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                var totalLoaded = e.loaded + fileState[id].loaded,
                    estTotalRequestsSize = calcAllRequestsSizeForChunkedUpload(id, chunkIdx, e.total);

                options.onProgress(id, name, totalLoaded, estTotalRequestsSize);
            }
        };

        options.onUploadChunk(id, name, internalApi.getChunkDataForCallback(chunkData));

        params = options.paramsStore.getParams(id);
        addChunkingSpecificParams(id, params, chunkData);

        if (fileState[id].attemptingResume) {
            addResumeSpecificParams(params);
        }

        toSend = setParamsAndGetEntityToSend(params, xhr, chunkData.blob, id);
        setHeaders(id, xhr);

        log('Sending chunked upload request for item ' + id + ": bytes " + (chunkData.start+1) + "-" + chunkData.end + " of " + size);
        xhr.send(toSend);
    }

    function calcAllRequestsSizeForChunkedUpload(id, chunkIdx, requestSize) {
        var chunkData = internalApi.getChunkData(id, chunkIdx),
            blobSize = chunkData.size,
            overhead = requestSize - blobSize,
            size = publicApi.getSize(id),
            chunkCount = chunkData.count,
            initialRequestOverhead = fileState[id].initialRequestOverhead,
            overheadDiff = overhead - initialRequestOverhead;

        fileState[id].lastRequestOverhead = overhead;

        if (chunkIdx === 0) {
            fileState[id].lastChunkIdxProgress = 0;
            fileState[id].initialRequestOverhead = overhead;
            fileState[id].estTotalRequestsSize = size + (chunkCount * overhead);
        }
        else if (fileState[id].lastChunkIdxProgress !== chunkIdx) {
            fileState[id].lastChunkIdxProgress = chunkIdx;
            fileState[id].estTotalRequestsSize += overheadDiff;
        }

        return fileState[id].estTotalRequestsSize;
    }

    function getLastRequestOverhead(id) {
        if (multipart) {
            return fileState[id].lastRequestOverhead;
        }
        else {
            return 0;
        }
    }

    function handleSuccessfullyCompletedChunk(id, response, xhr) {
        var chunkIdx = fileState[id].remainingChunkIdxs.shift(),
            chunkData = internalApi.getChunkData(id, chunkIdx);

        fileState[id].attemptingResume = false;
        fileState[id].loaded += chunkData.size + getLastRequestOverhead(id);

        if (fileState[id].remainingChunkIdxs.length > 0) {
            uploadNextChunk(id);
        }
        else {
            if (resumeEnabled) {
                deletePersistedChunkData(id);
            }

            handleCompletedItem(id, response, xhr);
        }
    }

    function isErrorResponse(xhr, response) {
        return xhr.status !== 200 || !response.success || response.reset;
    }

    function parseResponse(id, xhr) {
        var response;

        try {
            log(qq.format("Received response status {} with body: {}", xhr.status, xhr.responseText));

            response = qq.parseJson(xhr.responseText);

            if (response.newUuid !== undefined) {
                publicApi.setUuid(id, response.newUuid);
            }
        }
        catch(error) {
            log('Error when attempting to parse xhr response text (' + error.message + ')', 'error');
            response = {};
        }

        return response;
    }

    function handleResetResponse(id) {
        log('Server has ordered chunking effort to be restarted on next attempt for item ID ' + id, 'error');

        if (resumeEnabled) {
            deletePersistedChunkData(id);
            fileState[id].attemptingResume = false;
        }

        fileState[id].remainingChunkIdxs = [];
        delete fileState[id].loaded;
        delete fileState[id].estTotalRequestsSize;
        delete fileState[id].initialRequestOverhead;
    }

    function handleResetResponseOnResumeAttempt(id) {
        fileState[id].attemptingResume = false;
        log("Server has declared that it cannot handle resume for item ID " + id + " - starting from the first chunk", 'error');
        handleResetResponse(id);
        publicApi.upload(id, true);
    }

    function handleNonResetErrorResponse(id, response, xhr) {
        var name = publicApi.getName(id);

        if (options.onAutoRetry(id, name, response, xhr)) {
            return;
        }
        else {
            handleCompletedItem(id, response, xhr);
        }
    }

    function onComplete(id, xhr) {
        var response;

        // the request was aborted/cancelled
        if (!fileState[id]) {
            return;
        }

        log("xhr - server response received for " + id);
        log("responseText = " + xhr.responseText);
        response = parseResponse(id, xhr);

        if (isErrorResponse(xhr, response)) {
            if (response.reset) {
                handleResetResponse(id);
            }

            if (fileState[id].attemptingResume && response.reset) {
                handleResetResponseOnResumeAttempt(id);
            }
            else {
                handleNonResetErrorResponse(id, response, xhr);
            }
        }
        else if (chunkFiles) {
            handleSuccessfullyCompletedChunk(id, response, xhr);
        }
        else {
            handleCompletedItem(id, response, xhr);
        }
    }

    function getReadyStateChangeHandler(id, xhr) {
        return function() {
            if (xhr.readyState === 4) {
                onComplete(id, xhr);
            }
        };
    }

    function persistChunkData(id, chunkData) {
        var fileUuid = publicApi.getUuid(id),
            lastByteSent = fileState[id].loaded,
            initialRequestOverhead = fileState[id].initialRequestOverhead,
            estTotalRequestsSize = fileState[id].estTotalRequestsSize,
            cookieName = getChunkDataCookieName(id),
            cookieValue = fileUuid +
                cookieItemDelimiter + chunkData.part +
                cookieItemDelimiter + lastByteSent +
                cookieItemDelimiter + initialRequestOverhead +
                cookieItemDelimiter + estTotalRequestsSize,
            cookieExpDays = options.resume.cookiesExpireIn;

        qq.setCookie(cookieName, cookieValue, cookieExpDays);
    }

    function deletePersistedChunkData(id) {
        if (fileState[id].file) {
            var cookieName = getChunkDataCookieName(id);
            qq.deleteCookie(cookieName);
        }
    }

    function getPersistedChunkData(id) {
        var chunkCookieValue = qq.getCookie(getChunkDataCookieName(id)),
            filename = publicApi.getName(id),
            sections, uuid, partIndex, lastByteSent, initialRequestOverhead, estTotalRequestsSize;

        if (chunkCookieValue) {
            sections = chunkCookieValue.split(cookieItemDelimiter);

            if (sections.length === 5) {
                uuid = sections[0];
                partIndex = parseInt(sections[1], 10);
                lastByteSent = parseInt(sections[2], 10);
                initialRequestOverhead = parseInt(sections[3], 10);
                estTotalRequestsSize = parseInt(sections[4], 10);

                return {
                    uuid: uuid,
                    part: partIndex,
                    lastByteSent: lastByteSent,
                    initialRequestOverhead: initialRequestOverhead,
                    estTotalRequestsSize: estTotalRequestsSize
                };
            }
            else {
                log('Ignoring previously stored resume/chunk cookie for ' + filename + " - old cookie format", "warn");
            }
        }
    }

    function getChunkDataCookieName(id) {
        var filename = publicApi.getName(id),
            fileSize = publicApi.getSize(id),
            maxChunkSize = options.chunking.partSize,
            cookieName;

        cookieName = "qqfilechunk" + cookieItemDelimiter + encodeURIComponent(filename) + cookieItemDelimiter + fileSize + cookieItemDelimiter + maxChunkSize;

        if (resumeId !== undefined) {
            cookieName += cookieItemDelimiter + resumeId;
        }

        return cookieName;
    }

    function getResumeId() {
        if (options.resume.id !== null &&
            options.resume.id !== undefined &&
            !qq.isFunction(options.resume.id) &&
            !qq.isObject(options.resume.id)) {

            return options.resume.id;
        }
    }

    function calculateRemainingChunkIdxsAndUpload(id, firstChunkIndex) {
        var currentChunkIndex;

        for (currentChunkIndex = internalApi.getTotalChunks(id)-1; currentChunkIndex >= firstChunkIndex; currentChunkIndex-=1) {
            fileState[id].remainingChunkIdxs.unshift(currentChunkIndex);
        }

        uploadNextChunk(id);
    }

    function onResumeSuccess(id, name, firstChunkIndex, persistedChunkInfoForResume) {
        firstChunkIndex = persistedChunkInfoForResume.part;
        fileState[id].loaded = persistedChunkInfoForResume.lastByteSent;
        fileState[id].estTotalRequestsSize = persistedChunkInfoForResume.estTotalRequestsSize;
        fileState[id].initialRequestOverhead = persistedChunkInfoForResume.initialRequestOverhead;
        fileState[id].attemptingResume = true;
        log('Resuming ' + name + " at partition index " + firstChunkIndex);

        calculateRemainingChunkIdxsAndUpload(id, firstChunkIndex);
    }

    function handlePossibleResumeAttempt(id, persistedChunkInfoForResume, firstChunkIndex) {
        var name = publicApi.getName(id),
            firstChunkDataForResume = internalApi.getChunkData(id, persistedChunkInfoForResume.part),
            onResumeRetVal;

        onResumeRetVal = options.onResume(id, name, internalApi.getChunkDataForCallback(firstChunkDataForResume));
        if (qq.isPromise(onResumeRetVal)) {
            log("Waiting for onResume promise to be fulfilled for " + id);
            onResumeRetVal.then(
                function() {
                    onResumeSuccess(id, name, firstChunkIndex, persistedChunkInfoForResume);
                },
                function() {
                    log("onResume promise fulfilled - failure indicated.  Will not resume.")
                    calculateRemainingChunkIdxsAndUpload(id, firstChunkIndex);
                }
            );
        }
        else if (onResumeRetVal !== false) {
            onResumeSuccess(id, name, firstChunkIndex, persistedChunkInfoForResume);
        }
        else {
            log("onResume callback returned false.  Will not resume.");
            calculateRemainingChunkIdxsAndUpload(id, firstChunkIndex);
        }
    }

    function handleFileChunkingUpload(id, retry) {
        var firstChunkIndex = 0,
            persistedChunkInfoForResume;

        if (!fileState[id].remainingChunkIdxs || fileState[id].remainingChunkIdxs.length === 0) {
            fileState[id].remainingChunkIdxs = [];

            if (resumeEnabled && !retry && fileState[id].file) {
                persistedChunkInfoForResume = getPersistedChunkData(id);
                if (persistedChunkInfoForResume) {
                    handlePossibleResumeAttempt(id, persistedChunkInfoForResume, firstChunkIndex);
                }
                else {
                    calculateRemainingChunkIdxsAndUpload(id, firstChunkIndex);
                }
            }
            else {
                calculateRemainingChunkIdxsAndUpload(id, firstChunkIndex);
            }
        }
        else {
            uploadNextChunk(id);
        }
    }

    function handleStandardFileUpload(id) {
        var fileOrBlob = fileState[id].file || fileState[id].blobData.blob,
            name = publicApi.getName(id),
            xhr, params, toSend;

        fileState[id].loaded = 0;

        xhr = internalApi.createXhr(id);

        xhr.upload.onprogress = function(e){
            if (e.lengthComputable){
                fileState[id].loaded = e.loaded;
                options.onProgress(id, name, e.loaded, e.total);
            }
        };

        xhr.onreadystatechange = getReadyStateChangeHandler(id, xhr);

        params = options.paramsStore.getParams(id);
        toSend = setParamsAndGetEntityToSend(params, xhr, fileOrBlob, id);
        setHeaders(id, xhr);

        log('Sending upload request for ' + id);
        xhr.send(toSend);
    }

    function handleUploadSignal(id, retry) {
        var name = publicApi.getName(id);

        if (publicApi.isValid(id)) {
            options.onUpload(id, name);

            if (chunkFiles) {
                handleFileChunkingUpload(id, retry);
            }
            else {
                handleStandardFileUpload(id);
            }
        }
    }


    publicApi = new qq.UploadHandlerXhrApi(
        internalApi,
        fileState,
        chunkFiles ? options.chunking : null,
        handleUploadSignal,
        options.onCancel,
        onUuidChanged,
        log
    );

    // Base XHR API overrides
    qq.override(publicApi, function(super_) {
        return {
            add: function(fileOrBlobData) {
                var id = super_.add(fileOrBlobData),
                    persistedChunkData;

                if (resumeEnabled) {
                    persistedChunkData = getPersistedChunkData(id);

                    if (persistedChunkData) {
                        fileState[id].uuid = persistedChunkData.uuid;
                    }
                }

                return id;
            },

            getResumableFilesData: function() {
                var matchingCookieNames = [],
                    resumableFilesData = [];

                if (chunkFiles && resumeEnabled) {
                    if (resumeId === undefined) {
                        matchingCookieNames = qq.getCookieNames(new RegExp("^qqfilechunk\\" + cookieItemDelimiter + ".+\\" +
                            cookieItemDelimiter + "\\d+\\" + cookieItemDelimiter + options.chunking.partSize + "="));
                    }
                    else {
                        matchingCookieNames = qq.getCookieNames(new RegExp("^qqfilechunk\\" + cookieItemDelimiter + ".+\\" +
                            cookieItemDelimiter + "\\d+\\" + cookieItemDelimiter + options.chunking.partSize + "\\" +
                            cookieItemDelimiter + resumeId + "="));
                    }

                    qq.each(matchingCookieNames, function(idx, cookieName) {
                        var cookiesNameParts = cookieName.split(cookieItemDelimiter);
                        var cookieValueParts = qq.getCookie(cookieName).split(cookieItemDelimiter);

                        resumableFilesData.push({
                            name: decodeURIComponent(cookiesNameParts[1]),
                            size: cookiesNameParts[2],
                            uuid: cookieValueParts[0],
                            partIdx: cookieValueParts[1]
                        });
                    });

                    return resumableFilesData;
                }
                return [];
            },

            expunge: function(id) {
                if (resumeEnabled) {
                    deletePersistedChunkData(id);
                }

                super_.expunge(id);
            }
        };
    });

    return publicApi;
};
