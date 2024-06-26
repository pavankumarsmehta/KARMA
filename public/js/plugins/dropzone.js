"use strict";

function _typeof(obj) {
    "@babel/helpers - typeof";
    if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
        _typeof = function _typeof(obj) {
            return typeof obj;
        };
    } else {
        _typeof = function _typeof(obj) {
            return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
        };
    }
    return _typeof(obj);
}

function _inherits(subClass, superClass) {
    if (typeof superClass !== "function" && superClass !== null) {
        throw new TypeError("Super expression must either be null or a function");
    }
    subClass.prototype = Object.create(superClass && superClass.prototype, {
        constructor: {
            value: subClass,
            writable: true,
            configurable: true
        }
    });
    if (superClass) _setPrototypeOf(subClass, superClass);
}

function _setPrototypeOf(o, p) {
    _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
        o.__proto__ = p;
        return o;
    };
    return _setPrototypeOf(o, p);
}

function _createSuper(Derived) {
    var hasNativeReflectConstruct = _isNativeReflectConstruct();
    return function _createSuperInternal() {
        var Super = _getPrototypeOf(Derived),
            result;
        if (hasNativeReflectConstruct) {
            var NewTarget = _getPrototypeOf(this).constructor;
            result = Reflect.construct(Super, arguments, NewTarget);
        } else {
            result = Super.apply(this, arguments);
        }
        return _possibleConstructorReturn(this, result);
    };
}

function _possibleConstructorReturn(self, call) {
    if (call && (_typeof(call) === "object" || typeof call === "function")) {
        return call;
    }
    return _assertThisInitialized(self);
}

function _assertThisInitialized(self) {
    if (self === void 0) {
        throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
    }
    return self;
}

function _isNativeReflectConstruct() {
    if (typeof Reflect === "undefined" || !Reflect.construct) return false;
    if (Reflect.construct.sham) return false;
    if (typeof Proxy === "function") return true;
    try {
        Date.prototype.toString.call(Reflect.construct(Date, [], function() {}));
        return true;
    } catch (e) {
        return false;
    }
}

function _getPrototypeOf(o) {
    _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
        return o.__proto__ || Object.getPrototypeOf(o);
    };
    return _getPrototypeOf(o);
}

function _createForOfIteratorHelper(o, allowArrayLike) {
    var it;
    if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) {
        if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") {
            if (it) o = it;
            var i = 0;
            var F = function F() {};
            return {
                s: F,
                n: function n() {
                    if (i >= o.length) return {
                        done: true
                    };
                    return {
                        done: false,
                        value: o[i++]
                    };
                },
                e: function e(_e) {
                    throw _e;
                },
                f: F
            };
        }
        throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
    }
    var normalCompletion = true,
        didErr = false,
        err;
    return {
        s: function s() {
            it = o[Symbol.iterator]();
        },
        n: function n() {
            var step = it.next();
            normalCompletion = step.done;
            return step;
        },
        e: function e(_e2) {
            didErr = true;
            err = _e2;
        },
        f: function f() {
            try {
                if (!normalCompletion && it["return"] != null) it["return"]();
            } finally {
                if (didErr) throw err;
            }
        }
    };
}

function _unsupportedIterableToArray(o, minLen) {
    if (!o) return;
    if (typeof o === "string") return _arrayLikeToArray(o, minLen);
    var n = Object.prototype.toString.call(o).slice(8, -1);
    if (n === "Object" && o.constructor) n = o.constructor.name;
    if (n === "Map" || n === "Set") return Array.from(o);
    if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
}

function _arrayLikeToArray(arr, len) {
    if (len == null || len > arr.length) len = arr.length;
    for (var i = 0, arr2 = new Array(len); i < len; i++) {
        arr2[i] = arr[i];
    }
    return arr2;
}

function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
        throw new TypeError("Cannot call a class as a function");
    }
}

function _defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
        var descriptor = props[i];
        descriptor.enumerable = descriptor.enumerable || false;
        descriptor.configurable = true;
        if ("value" in descriptor) descriptor.writable = true;
        Object.defineProperty(target, descriptor.key, descriptor);
    }
}

function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    return Constructor;
}
var Emitter = function() {
    function Emitter() {
        _classCallCheck(this, Emitter);
    }
    _createClass(Emitter, [{
        key: "on",
        value: function on(event, fn) {
            this._callbacks = this._callbacks || {};
            if (!this._callbacks[event]) {
                this._callbacks[event] = [];
            }
            this._callbacks[event].push(fn);
            return this;
        }
    }, {
        key: "emit",
        value: function emit(event) {
            this._callbacks = this._callbacks || {};
            var callbacks = this._callbacks[event];
            if (callbacks) {
                for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
                    args[_key - 1] = arguments[_key];
                }
                var _iterator = _createForOfIteratorHelper(callbacks),
                    _step;
                try {
                    for (_iterator.s(); !(_step = _iterator.n()).done;) {
                        var callback = _step.value;
                        callback.apply(this, args);
                    }
                } catch (err) {
                    _iterator.e(err);
                } finally {
                    _iterator.f();
                }
            }
            return this;
        }
    }, {
        key: "off",
        value: function off(event, fn) {
            if (!this._callbacks || arguments.length === 0) {
                this._callbacks = {};
                return this;
            }
            var callbacks = this._callbacks[event];
            if (!callbacks) {
                return this;
            }
            if (arguments.length === 1) {
                delete this._callbacks[event];
                return this;
            }
            for (var i = 0; i < callbacks.length; i++) {
                var callback = callbacks[i];
                if (callback === fn) {
                    callbacks.splice(i, 1);
                    break;
                }
            }
            return this;
        }
    }]);
    return Emitter;
}();
var Dropzone = function(_Emitter) {
    _inherits(Dropzone, _Emitter);
    var _super = _createSuper(Dropzone);
    _createClass(Dropzone, null, [{
        key: "initClass",
        value: function initClass() {
            this.prototype.Emitter = Emitter;
            this.prototype.events = ["drop", "dragstart", "dragend", "dragenter", "dragover", "dragleave", "addedfile", "addedfiles", "removedfile", "thumbnail", "error", "errormultiple", "processing", "processingmultiple", "uploadprogress", "totaluploadprogress", "sending", "sendingmultiple", "success", "successmultiple", "canceled", "canceledmultiple", "complete", "completemultiple", "reset", "maxfilesexceeded", "maxfilesreached", "queuecomplete"];
            this.prototype.defaultOptions = {
                url: null,
                method: "post",
                withCredentials: false,
                timeout: 30000,
                parallelUploads: 2,
                uploadMultiple: false,
                chunking: false,
                forceChunking: false,
                chunkSize: 2000000,
                parallelChunkUploads: false,
                retryChunks: false,
                retryChunksLimit: 3,
                maxFilesize: 256,
                paramName: "file",
                createImageThumbnails: true,
                maxThumbnailFilesize: 10,
                thumbnailWidth: 120,
                thumbnailHeight: 120,
                thumbnailMethod: 'crop',
                resizeWidth: null,
                resizeHeight: null,
                resizeMimeType: null,
                resizeQuality: 0.8,
                resizeMethod: 'contain',
                filesizeBase: 1000,
                maxFiles: null,
                headers: null,
                clickable: true,
                ignoreHiddenFiles: true,
                acceptedFiles: null,
                acceptedMimeTypes: null,
                autoProcessQueue: true,
                autoQueue: true,
                addRemoveLinks: false,
                previewsContainer: null,
                hiddenInputContainer: "body",
                capture: null,
                renameFilename: null,
                renameFile: null,
                forceFallback: false,
                dictDefaultMessage: "Drop files here to upload",
                dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
                dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
                dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
                dictInvalidFileType: "You can't upload files of this type.",
                dictResponseError: "Server responded with {{statusCode}} code.",
                dictCancelUpload: "Cancel upload",
                dictUploadCanceled: "Upload canceled.",
                dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
                dictRemoveFile: "Remove file",
                dictRemoveFileConfirmation: null,
                dictMaxFilesExceeded: "You can not upload any more files.",
                dictFileSizeUnits: {
                    tb: "TB",
                    gb: "GB",
                    mb: "MB",
                    kb: "KB",
                    b: "b"
                },
                init: function init() {},
                params: function params(files, xhr, chunk) {
                    if (chunk) {
                        return {
                            dzuuid: chunk.file.upload.uuid,
                            dzchunkindex: chunk.index,
                            dztotalfilesize: chunk.file.size,
                            dzchunksize: this.options.chunkSize,
                            dztotalchunkcount: chunk.file.upload.totalChunkCount,
                            dzchunkbyteoffset: chunk.index * this.options.chunkSize
                        };
                    }
                },
                accept: function accept(file, done) {
                    return done();
                },
                chunksUploaded: function chunksUploaded(file, done) {
                    done();
                },
                fallback: function fallback() {
                    var messageElement;
                    this.element.className = "".concat(this.element.className, " dz-browser-not-supported");
                    var _iterator2 = _createForOfIteratorHelper(this.element.getElementsByTagName("div")),
                        _step2;
                    try {
                        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
                            var child = _step2.value;
                            if (/(^| )dz-message($| )/.test(child.className)) {
                                messageElement = child;
                                child.className = "dz-message";
                                break;
                            }
                        }
                    } catch (err) {
                        _iterator2.e(err);
                    } finally {
                        _iterator2.f();
                    }
                    if (!messageElement) {
                        messageElement = Dropzone.createElement("<div class=\"dz-message\"><span></span></div>");
                        this.element.appendChild(messageElement);
                    }
                    var span = messageElement.getElementsByTagName("span")[0];
                    if (span) {
                        if (span.textContent != null) {
                            span.textContent = this.options.dictFallbackMessage;
                        } else if (span.innerText != null) {
                            span.innerText = this.options.dictFallbackMessage;
                        }
                    }
                    return this.element.appendChild(this.getFallbackForm());
                },
                resize: function resize(file, width, height, resizeMethod) {
                    var info = {
                        srcX: 0,
                        srcY: 0,
                        srcWidth: file.width,
                        srcHeight: file.height
                    };
                    var srcRatio = file.width / file.height;
                    if (width == null && height == null) {
                        width = info.srcWidth;
                        height = info.srcHeight;
                    } else if (width == null) {
                        width = height * srcRatio;
                    } else if (height == null) {
                        height = width / srcRatio;
                    }
                    width = Math.min(width, info.srcWidth);
                    height = Math.min(height, info.srcHeight);
                    var trgRatio = width / height;
                    if (info.srcWidth > width || info.srcHeight > height) {
                        if (resizeMethod === 'crop') {
                            if (srcRatio > trgRatio) {
                                info.srcHeight = file.height;
                                info.srcWidth = info.srcHeight * trgRatio;
                            } else {
                                info.srcWidth = file.width;
                                info.srcHeight = info.srcWidth / trgRatio;
                            }
                        } else if (resizeMethod === 'contain') {
                            if (srcRatio > trgRatio) {
                                height = width / srcRatio;
                            } else {
                                width = height * srcRatio;
                            }
                        } else {
                            throw new Error("Unknown resizeMethod '".concat(resizeMethod, "'"));
                        }
                    }
                    info.srcX = (file.width - info.srcWidth) / 2;
                    info.srcY = (file.height - info.srcHeight) / 2;
                    info.trgWidth = width;
                    info.trgHeight = height;
                    return info;
                },
                transformFile: function transformFile(file, done) {
                    if ((this.options.resizeWidth || this.options.resizeHeight) && file.type.match(/image.*/)) {
                        return this.resizeImage(file, this.options.resizeWidth, this.options.resizeHeight, this.options.resizeMethod, done);
                    } else {
                        return done(file);
                    }
                },
                previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail /></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\">\n      <title>Check</title>\n      <g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\">\n      <title>Error</title>\n      <g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\n        <g stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n</div>",
                drop: function drop(e) {
                    return this.element.classList.remove("dz-drag-hover");
                },
                dragstart: function dragstart(e) {},
                dragend: function dragend(e) {
                    return this.element.classList.remove("dz-drag-hover");
                },
                dragenter: function dragenter(e) {
                    return this.element.classList.add("dz-drag-hover");
                },
                dragover: function dragover(e) {
                    return this.element.classList.add("dz-drag-hover");
                },
                dragleave: function dragleave(e) {
                    return this.element.classList.remove("dz-drag-hover");
                },
                paste: function paste(e) {},
                reset: function reset() {
                    return this.element.classList.remove("dz-started");
                },
                addedfile: function addedfile(file) {
                    var _this2 = this;
                    if (this.element === this.previewsContainer) {
                        this.element.classList.add("dz-started");
                    }
                    if (this.previewsContainer) {
                        file.previewElement = Dropzone.createElement(this.options.previewTemplate.trim());
                        file.previewTemplate = file.previewElement;
                        this.previewsContainer.appendChild(file.previewElement);
                        var _iterator3 = _createForOfIteratorHelper(file.previewElement.querySelectorAll("[data-dz-name]")),
                            _step3;
                        try {
                            for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
                                var node = _step3.value;
                                node.textContent = file.name;
                            }
                        } catch (err) {
                            _iterator3.e(err);
                        } finally {
                            _iterator3.f();
                        }
                        var _iterator4 = _createForOfIteratorHelper(file.previewElement.querySelectorAll("[data-dz-size]")),
                            _step4;
                        try {
                            for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
                                node = _step4.value;
                                node.innerHTML = this.filesize(file.size);
                            }
                        } catch (err) {
                            _iterator4.e(err);
                        } finally {
                            _iterator4.f();
                        }
                        if (this.options.addRemoveLinks) {
                            file._removeLink = Dropzone.createElement("<a class=\"dz-remove\" href=\"javascript:undefined;\" data-dz-remove>".concat(this.options.dictRemoveFile, "</a>"));
                            file.previewElement.appendChild(file._removeLink);
                        }
                        var removeFileEvent = function removeFileEvent(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            if (file.status === Dropzone.UPLOADING) {
                                return Dropzone.confirm(_this2.options.dictCancelUploadConfirmation, function() {
                                    return _this2.removeFile(file);
                                });
                            } else {
                                if (_this2.options.dictRemoveFileConfirmation) {
                                    return Dropzone.confirm(_this2.options.dictRemoveFileConfirmation, function() {
                                        return _this2.removeFile(file);
                                    });
                                } else {
                                    return _this2.removeFile(file);
                                }
                            }
                        };
                        var _iterator5 = _createForOfIteratorHelper(file.previewElement.querySelectorAll("[data-dz-remove]")),
                            _step5;
                        try {
                            for (_iterator5.s(); !(_step5 = _iterator5.n()).done;) {
                                var removeLink = _step5.value;
                                removeLink.addEventListener("click", removeFileEvent);
                            }
                        } catch (err) {
                            _iterator5.e(err);
                        } finally {
                            _iterator5.f();
                        }
                    }

                },
                removedfile: function removedfile(file) {
                    if (file.previewElement != null && file.previewElement.parentNode != null) {
                        file.previewElement.parentNode.removeChild(file.previewElement);
                    }
                    return this._updateMaxFilesReachedClass();
                },
                thumbnail: function thumbnail(file, dataUrl) {
                    if (file.previewElement) {
                        file.previewElement.classList.remove("dz-file-preview");
                        var _iterator6 = _createForOfIteratorHelper(file.previewElement.querySelectorAll("[data-dz-thumbnail]")),
                            _step6;
                        try {
                            for (_iterator6.s(); !(_step6 = _iterator6.n()).done;) {
                                var thumbnailElement = _step6.value;
                                thumbnailElement.alt = file.name;
                                thumbnailElement.src = dataUrl;
                            }
                        } catch (err) {
                            _iterator6.e(err);
                        } finally {
                            _iterator6.f();
                        }
                        return setTimeout(function() {
                            return file.previewElement.classList.add("dz-image-preview");
                        }, 1);
                    }
                },
                error: function error(file, message) {
                    if (file.previewElement) {
                        file.previewElement.classList.add("dz-error");
                        if (typeof message !== "string" && message.error) {
                            message = message.error;
                        }
                        var _iterator7 = _createForOfIteratorHelper(file.previewElement.querySelectorAll("[data-dz-errormessage]")),
                            _step7;
                        try {
                            for (_iterator7.s(); !(_step7 = _iterator7.n()).done;) {
                                var node = _step7.value;
                                node.textContent = message;
                            }
                        } catch (err) {
                            _iterator7.e(err);
                        } finally {
                            _iterator7.f();
                        }
                    }
                },
                errormultiple: function errormultiple() {},
                processing: function processing(file) {
                    if (file.previewElement) {
                        file.previewElement.classList.add("dz-processing");
                        if (file._removeLink) {
                            return file._removeLink.innerHTML = this.options.dictCancelUpload;
                        }
                    }
                },
                processingmultiple: function processingmultiple() {},
                uploadprogress: function uploadprogress(file, progress, bytesSent) {
                    if (file.previewElement) {
                        var _iterator8 = _createForOfIteratorHelper(file.previewElement.querySelectorAll("[data-dz-uploadprogress]")),
                            _step8;
                        try {
                            for (_iterator8.s(); !(_step8 = _iterator8.n()).done;) {
                                var node = _step8.value;
                                node.nodeName === 'PROGRESS' ? node.value = progress : node.style.width = "".concat(progress, "%");
                            }
                        } catch (err) {
                            _iterator8.e(err);
                        } finally {
                            _iterator8.f();
                        }
                    }
                },
                totaluploadprogress: function totaluploadprogress() {},
                sending: function sending() {},
                sendingmultiple: function sendingmultiple() {},
                success: function success(file) {
                    if (file.previewElement) {
                        return file.previewElement.classList.add("dz-success");
                    }
                },
                successmultiple: function successmultiple() {},
                canceled: function canceled(file) {
                    return this.emit("error", file, this.options.dictUploadCanceled);
                },
                canceledmultiple: function canceledmultiple() {},
                complete: function complete(file) {
                    if (file._removeLink) {
                        file._removeLink.innerHTML = this.options.dictRemoveFile;
                    }
                    if (file.previewElement) {
                        return file.previewElement.classList.add("dz-complete");
                    }
                },
                completemultiple: function completemultiple() {},
                maxfilesexceeded: function maxfilesexceeded() {},
                maxfilesreached: function maxfilesreached() {},
                queuecomplete: function queuecomplete() {},
                addedfiles: function addedfiles() {}
            };
            this.prototype._thumbnailQueue = [];
            this.prototype._processingThumbnail = false;
        }
    }, {
        key: "extend",
        value: function extend(target) {
            for (var _len2 = arguments.length, objects = new Array(_len2 > 1 ? _len2 - 1 : 0), _key2 = 1; _key2 < _len2; _key2++) {
                objects[_key2 - 1] = arguments[_key2];
            }
            for (var _i = 0, _objects = objects; _i < _objects.length; _i++) {
                var object = _objects[_i];
                for (var key in object) {
                    var val = object[key];
                    target[key] = val;
                }
            }
            return target;
        }
    }]);

    function Dropzone(el, options) {
        var _this;
        _classCallCheck(this, Dropzone);
        _this = _super.call(this);
        var fallback, left;
        _this.element = el;
        _this.version = Dropzone.version;
        _this.defaultOptions.previewTemplate = _this.defaultOptions.previewTemplate.replace(/\n*/g, "");
        _this.clickableElements = [];
        _this.listeners = [];
        _this.files = [];
        if (typeof _this.element === "string") {
            _this.element = document.querySelector(_this.element);
        }
        if (!_this.element || _this.element.nodeType == null) {
            throw new Error("Invalid dropzone element.");
        }
        if (_this.element.dropzone) {
            throw new Error("Dropzone already attached.");
        }
        Dropzone.instances.push(_assertThisInitialized(_this));
        _this.element.dropzone = _assertThisInitialized(_this);
        var elementOptions = (left = Dropzone.optionsForElement(_this.element)) != null ? left : {};
        _this.options = Dropzone.extend({}, _this.defaultOptions, elementOptions, options != null ? options : {});
        if (_this.options.forceFallback || !Dropzone.isBrowserSupported()) {
            return _possibleConstructorReturn(_this, _this.options.fallback.call(_assertThisInitialized(_this)));
        }
        if (_this.options.url == null) {
            _this.options.url = _this.element.getAttribute("action");
        }
        if (!_this.options.url) {
            throw new Error("No URL provided.");
        }
        if (_this.options.acceptedFiles && _this.options.acceptedMimeTypes) {
            throw new Error("You can't provide both 'acceptedFiles' and 'acceptedMimeTypes'. 'acceptedMimeTypes' is deprecated.");
        }
        if (_this.options.uploadMultiple && _this.options.chunking) {
            throw new Error('You cannot set both: uploadMultiple and chunking.');
        }
        if (_this.options.acceptedMimeTypes) {
            _this.options.acceptedFiles = _this.options.acceptedMimeTypes;
            delete _this.options.acceptedMimeTypes;
        }
        if (_this.options.renameFilename != null) {
            _this.options.renameFile = function(file) {
                return _this.options.renameFilename.call(_assertThisInitialized(_this), file.name, file);
            };
        }
        if (typeof _this.options.method === 'string') {
            _this.options.method = _this.options.method.toUpperCase();
        }
        if ((fallback = _this.getExistingFallback()) && fallback.parentNode) {
            fallback.parentNode.removeChild(fallback);
        }
        if (_this.options.previewsContainer !== false) {
            if (_this.options.previewsContainer) {
                _this.previewsContainer = Dropzone.getElement(_this.options.previewsContainer, "previewsContainer");
            } else {
                _this.previewsContainer = _this.element;
            }
        }
        if (_this.options.clickable) {
            if (_this.options.clickable === true) {
                _this.clickableElements = [_this.element];
            } else {
                _this.clickableElements = Dropzone.getElements(_this.options.clickable, "clickable");
            }
        }
        _this.init();
        return _this;
    }
    _createClass(Dropzone, [{
        key: "getAcceptedFiles",
        value: function getAcceptedFiles() {
            return this.files.filter(function(file) {
                return file.accepted;
            }).map(function(file) {
                return file;
            });
        }
    }, {
        key: "getRejectedFiles",
        value: function getRejectedFiles() {
            return this.files.filter(function(file) {
                return !file.accepted;
            }).map(function(file) {
                return file;
            });
        }
    }, {
        key: "getFilesWithStatus",
        value: function getFilesWithStatus(status) {
            return this.files.filter(function(file) {
                return file.status === status;
            }).map(function(file) {
                return file;
            });
        }
    }, {
        key: "getQueuedFiles",
        value: function getQueuedFiles() {
            return this.getFilesWithStatus(Dropzone.QUEUED);
        }
    }, {
        key: "getUploadingFiles",
        value: function getUploadingFiles() {
            return this.getFilesWithStatus(Dropzone.UPLOADING);
        }
    }, {
        key: "getAddedFiles",
        value: function getAddedFiles() {
            return this.getFilesWithStatus(Dropzone.ADDED);
        }
    }, {
        key: "getActiveFiles",
        value: function getActiveFiles() {
            return this.files.filter(function(file) {
                return file.status === Dropzone.UPLOADING || file.status === Dropzone.QUEUED;
            }).map(function(file) {
                return file;
            });
        }
    }, {
        key: "init",
        value: function init() {
            var _this3 = this;
            if (this.element.tagName === "form") {
                this.element.setAttribute("enctype", "multipart/form-data");
            }
            if (this.element.classList.contains("dropzone") && !this.element.querySelector(".dz-message")) {
                this.element.appendChild(Dropzone.createElement("<div class=\"dz-default dz-message\"><button class=\"dz-button\" type=\"button\">".concat(this.options.dictDefaultMessage, "</button></div>")));
            }
            if (this.clickableElements.length) {
                var setupHiddenFileInput = function setupHiddenFileInput() {
                    if (_this3.hiddenFileInput) {
                        _this3.hiddenFileInput.parentNode.removeChild(_this3.hiddenFileInput);
                    }
                    _this3.hiddenFileInput = document.createElement("input");
                    _this3.hiddenFileInput.setAttribute("type", "file");
                    if (_this3.options.maxFiles === null || _this3.options.maxFiles > 1) {
                        _this3.hiddenFileInput.setAttribute("multiple", "multiple");
                    }
                    _this3.hiddenFileInput.className = "dz-hidden-input";
                    if (_this3.options.acceptedFiles !== null) {
                        _this3.hiddenFileInput.setAttribute("accept", _this3.options.acceptedFiles);
                    }
                    if (_this3.options.capture !== null) {
                        _this3.hiddenFileInput.setAttribute("capture", _this3.options.capture);
                    }
                    _this3.hiddenFileInput.style.visibility = "hidden";
                    _this3.hiddenFileInput.style.position = "absolute";
                    _this3.hiddenFileInput.style.top = "0";
                    _this3.hiddenFileInput.style.left = "0";
                    _this3.hiddenFileInput.style.height = "0";
                    _this3.hiddenFileInput.style.width = "0";
                    Dropzone.getElement(_this3.options.hiddenInputContainer, 'hiddenInputContainer').appendChild(_this3.hiddenFileInput);
                    return _this3.hiddenFileInput.addEventListener("change", function() {
                        var files = _this3.hiddenFileInput.files;
                        if (files.length) {
                            var _iterator9 = _createForOfIteratorHelper(files),
                                _step9;
                            try {
                                for (_iterator9.s(); !(_step9 = _iterator9.n()).done;) {
                                    var file = _step9.value;
                                    _this3.addFile(file);
                                }
                            } catch (err) {
                                _iterator9.e(err);
                            } finally {
                                _iterator9.f();
                            }
                        }
                        _this3.emit("addedfiles", files);
                        return setupHiddenFileInput();
                    });
                };
                setupHiddenFileInput();
            }
            this.URL = window.URL !== null ? window.URL : window.webkitURL;
            var _iterator10 = _createForOfIteratorHelper(this.events),
                _step10;
            try {
                for (_iterator10.s(); !(_step10 = _iterator10.n()).done;) {
                    var eventName = _step10.value;
                    this.on(eventName, this.options[eventName]);
                }
            } catch (err) {
                _iterator10.e(err);
            } finally {
                _iterator10.f();
            }
            this.on("uploadprogress", function() {
                return _this3.updateTotalUploadProgress();
            });
            this.on("removedfile", function() {
                return _this3.updateTotalUploadProgress();
            });
            this.on("canceled", function(file) {
                return _this3.emit("complete", file);
            });
            this.on("complete", function(file) {
                if (_this3.getAddedFiles().length === 0 && _this3.getUploadingFiles().length === 0 && _this3.getQueuedFiles().length === 0) {
                    return setTimeout(function() {
                        return _this3.emit("queuecomplete");
                    }, 0);
                }
            });
            var containsFiles = function containsFiles(e) {
                if (e.dataTransfer.types) {
                    for (var i = 0; i < e.dataTransfer.types.length; i++) {
                        if (e.dataTransfer.types[i] === "Files") return true;
                    }
                }
                return false;
            };
            var noPropagation = function noPropagation(e) {
                if (!containsFiles(e)) return;
                e.stopPropagation();
                if (e.preventDefault) {
                    return e.preventDefault();
                } else {
                    return e.returnValue = false;
                }
            };
            this.listeners = [{
                element: this.element,
                events: {
                    "dragstart": function dragstart(e) {
                        return _this3.emit("dragstart", e);
                    },
                    "dragenter": function dragenter(e) {
                        noPropagation(e);
                        return _this3.emit("dragenter", e);
                    },
                    "dragover": function dragover(e) {
                        var efct;
                        try {
                            efct = e.dataTransfer.effectAllowed;
                        } catch (error) {}
                        e.dataTransfer.dropEffect = 'move' === efct || 'linkMove' === efct ? 'move' : 'copy';
                        noPropagation(e);
                        return _this3.emit("dragover", e);
                    },
                    "dragleave": function dragleave(e) {
                        return _this3.emit("dragleave", e);
                    },
                    "drop": function drop(e) {
                        noPropagation(e);
                        return _this3.drop(e);
                    },
                    "dragend": function dragend(e) {
                        return _this3.emit("dragend", e);
                    }
                }
            }];
            this.clickableElements.forEach(function(clickableElement) {
                return _this3.listeners.push({
                    element: clickableElement,
                    events: {
                        "click": function click(evt) {
                            if (clickableElement !== _this3.element || evt.target === _this3.element || Dropzone.elementInside(evt.target, _this3.element.querySelector(".dz-message"))) {
                                _this3.hiddenFileInput.click();
                            }
                            return true;
                        }
                    }
                });
            });
            this.enable();
            return this.options.init.call(this);
        }
    }, {
        key: "destroy",
        value: function destroy() {
            this.disable();
            this.removeAllFiles(true);
            if (this.hiddenFileInput != null ? this.hiddenFileInput.parentNode : undefined) {
                this.hiddenFileInput.parentNode.removeChild(this.hiddenFileInput);
                this.hiddenFileInput = null;
            }
            delete this.element.dropzone;
            return Dropzone.instances.splice(Dropzone.instances.indexOf(this), 1);
        }
    }, {
        key: "updateTotalUploadProgress",
        value: function updateTotalUploadProgress() {
            var totalUploadProgress;
            var totalBytesSent = 0;
            var totalBytes = 0;
            var activeFiles = this.getActiveFiles();
            if (activeFiles.length) {
                var _iterator11 = _createForOfIteratorHelper(this.getActiveFiles()),
                    _step11;
                try {
                    for (_iterator11.s(); !(_step11 = _iterator11.n()).done;) {
                        var file = _step11.value;
                        totalBytesSent += file.upload.bytesSent;
                        totalBytes += file.upload.total;
                    }
                } catch (err) {
                    _iterator11.e(err);
                } finally {
                    _iterator11.f();
                }
                totalUploadProgress = 100 * totalBytesSent / totalBytes;
            } else {
                totalUploadProgress = 100;
            }
            return this.emit("totaluploadprogress", totalUploadProgress, totalBytes, totalBytesSent);
        }
    }, {
        key: "_getParamName",
        value: function _getParamName(n) {
            if (typeof this.options.paramName === "function") {
                return this.options.paramName(n);
            } else {
                return "".concat(this.options.paramName).concat(this.options.uploadMultiple ? "[".concat(n, "]") : "");
            }
        }
    }, {
        key: "_renameFile",
        value: function _renameFile(file) {
            if (typeof this.options.renameFile !== "function") {
                return file.name;
            }
            return this.options.renameFile(file);
        }
    }, {
        key: "getFallbackForm",
        value: function getFallbackForm() {
            var existingFallback, form;
            if (existingFallback = this.getExistingFallback()) {
                return existingFallback;
            }
            var fieldsString = "<div class=\"dz-fallback\">";
            if (this.options.dictFallbackText) {
                fieldsString += "<p>".concat(this.options.dictFallbackText, "</p>");
            }
            fieldsString += "<input type=\"file\" name=\"".concat(this._getParamName(0), "\" ").concat(this.options.uploadMultiple ? 'multiple="multiple"' : undefined, " /><input type=\"submit\" value=\"Upload!\"></div>");
            var fields = Dropzone.createElement(fieldsString);
            if (this.element.tagName !== "FORM") {
                form = Dropzone.createElement("<form action=\"".concat(this.options.url, "\" enctype=\"multipart/form-data\" method=\"").concat(this.options.method, "\"></form>"));
                form.appendChild(fields);
            } else {
                this.element.setAttribute("enctype", "multipart/form-data");
                this.element.setAttribute("method", this.options.method);
            }
            return form != null ? form : fields;
        }
    }, {
        key: "getExistingFallback",
        value: function getExistingFallback() {
            var getFallback = function getFallback(elements) {
                var _iterator12 = _createForOfIteratorHelper(elements),
                    _step12;
                try {
                    for (_iterator12.s(); !(_step12 = _iterator12.n()).done;) {
                        var el = _step12.value;
                        if (/(^| )fallback($| )/.test(el.className)) {
                            return el;
                        }
                    }
                } catch (err) {
                    _iterator12.e(err);
                } finally {
                    _iterator12.f();
                }
            };
            for (var _i2 = 0, _arr = ["div", "form"]; _i2 < _arr.length; _i2++) {
                var tagName = _arr[_i2];
                var fallback;
                if (fallback = getFallback(this.element.getElementsByTagName(tagName))) {
                    return fallback;
                }
            }
        }
    }, {
        key: "setupEventListeners",
        value: function setupEventListeners() {
            return this.listeners.map(function(elementListeners) {
                return function() {
                    var result = [];
                    for (var event in elementListeners.events) {
                        var listener = elementListeners.events[event];
                        result.push(elementListeners.element.addEventListener(event, listener, false));
                    }
                    return result;
                }();
            });
        }
    }, {
        key: "removeEventListeners",
        value: function removeEventListeners() {
            return this.listeners.map(function(elementListeners) {
                return function() {
                    var result = [];
                    for (var event in elementListeners.events) {
                        var listener = elementListeners.events[event];
                        result.push(elementListeners.element.removeEventListener(event, listener, false));
                    }
                    return result;
                }();
            });
        }
    }, {
        key: "disable",
        value: function disable() {
            var _this4 = this;
            this.clickableElements.forEach(function(element) {
                return element.classList.remove("dz-clickable");
            });
            this.removeEventListeners();
            this.disabled = true;
            return this.files.map(function(file) {
                return _this4.cancelUpload(file);
            });
        }
    }, {
        key: "enable",
        value: function enable() {
            delete this.disabled;
            this.clickableElements.forEach(function(element) {
                return element.classList.add("dz-clickable");
            });
            return this.setupEventListeners();
        }
    }, {
        key: "filesize",
        value: function filesize(size) {
            var selectedSize = 0;
            var selectedUnit = "b";
            if (size > 0) {
                var units = ['tb', 'gb', 'mb', 'kb', 'b'];
                for (var i = 0; i < units.length; i++) {
                    var unit = units[i];
                    var cutoff = Math.pow(this.options.filesizeBase, 4 - i) / 10;
                    if (size >= cutoff) {
                        selectedSize = size / Math.pow(this.options.filesizeBase, 4 - i);
                        selectedUnit = unit;
                        break;
                    }
                }
                selectedSize = Math.round(10 * selectedSize) / 10;
            }
            return "<strong>".concat(selectedSize, "</strong> ").concat(this.options.dictFileSizeUnits[selectedUnit]);
        }
    }, {
        key: "_updateMaxFilesReachedClass",
        value: function _updateMaxFilesReachedClass() {
            if (this.options.maxFiles != null && this.getAcceptedFiles().length >= this.options.maxFiles) {
                if (this.getAcceptedFiles().length === this.options.maxFiles) {
                    this.emit('maxfilesreached', this.files);
                }
                return this.element.classList.add("dz-max-files-reached");
            } else {
                return this.element.classList.remove("dz-max-files-reached");
            }
        }
    }, {
        key: "drop",
        value: function drop(e) {
            if (!e.dataTransfer) {
                return;
            }
            this.emit("drop", e);
            var files = [];
            for (var i = 0; i < e.dataTransfer.files.length; i++) {
                files[i] = e.dataTransfer.files[i];
            }
            if (files.length) {
                var items = e.dataTransfer.items;
                if (items && items.length && items[0].webkitGetAsEntry != null) {
                    this._addFilesFromItems(items);
                } else {
                    this.handleFiles(files);
                }
            }
            this.emit("addedfiles", files);
        }
    }, {
        key: "paste",
        value: function paste(e) {
            if (__guard__(e != null ? e.clipboardData : undefined, function(x) {
                    return x.items;
                }) == null) {
                return;
            }
            this.emit("paste", e);
            var items = e.clipboardData.items;
            if (items.length) {
                return this._addFilesFromItems(items);
            }
        }
    }, {
        key: "handleFiles",
        value: function handleFiles(files) {
            var _iterator13 = _createForOfIteratorHelper(files),
                _step13;
            try {
                for (_iterator13.s(); !(_step13 = _iterator13.n()).done;) {
                    var file = _step13.value;
                    this.addFile(file);
                }
            } catch (err) {
                _iterator13.e(err);
            } finally {
                _iterator13.f();
            }
        }
    }, {
        key: "_addFilesFromItems",
        value: function _addFilesFromItems(items) {
            var _this5 = this;
            return function() {
                var result = [];
                var _iterator14 = _createForOfIteratorHelper(items),
                    _step14;
                try {
                    for (_iterator14.s(); !(_step14 = _iterator14.n()).done;) {
                        var item = _step14.value;
                        var entry;
                        if (item.webkitGetAsEntry != null && (entry = item.webkitGetAsEntry())) {
                            if (entry.isFile) {
                                result.push(_this5.addFile(item.getAsFile()));
                            } else if (entry.isDirectory) {
                                result.push(_this5._addFilesFromDirectory(entry, entry.name));
                            } else {
                                result.push(undefined);
                            }
                        } else if (item.getAsFile != null) {
                            if (item.kind == null || item.kind === "file") {
                                result.push(_this5.addFile(item.getAsFile()));
                            } else {
                                result.push(undefined);
                            }
                        } else {
                            result.push(undefined);
                        }
                    }
                } catch (err) {
                    _iterator14.e(err);
                } finally {
                    _iterator14.f();
                }
                return result;
            }();
        }
    }, {
        key: "_addFilesFromDirectory",
        value: function _addFilesFromDirectory(directory, path) {
            var _this6 = this;
            var dirReader = directory.createReader();
            var errorHandler = function errorHandler(error) {
                return __guardMethod__(console, 'log', function(o) {
                    return o.log(error);
                });
            };
            var readEntries = function readEntries() {
                return dirReader.readEntries(function(entries) {
                    if (entries.length > 0) {
                        var _iterator15 = _createForOfIteratorHelper(entries),
                            _step15;
                        try {
                            for (_iterator15.s(); !(_step15 = _iterator15.n()).done;) {
                                var entry = _step15.value;
                                if (entry.isFile) {
                                    entry.file(function(file) {
                                        if (_this6.options.ignoreHiddenFiles && file.name.substring(0, 1) === '.') {
                                            return;
                                        }
                                        file.fullPath = "".concat(path, "/").concat(file.name);
                                        return _this6.addFile(file);
                                    });
                                } else if (entry.isDirectory) {
                                    _this6._addFilesFromDirectory(entry, "".concat(path, "/").concat(entry.name));
                                }
                            }
                        } catch (err) {
                            _iterator15.e(err);
                        } finally {
                            _iterator15.f();
                        }
                        readEntries();
                    }
                    return null;
                }, errorHandler);
            };
            return readEntries();
        }
    }, {
        key: "accept",
        value: function accept(file, done) {
            if (this.options.maxFilesize && file.size > this.options.maxFilesize * 1024 * 1024) {
                done(this.options.dictFileTooBig.replace("{{filesize}}", Math.round(file.size / 1024 / 10.24) / 100).replace("{{maxFilesize}}", this.options.maxFilesize));
            } else if (!Dropzone.isValidFile(file, this.options.acceptedFiles)) {
                done(this.options.dictInvalidFileType);
            } else if (this.options.maxFiles != null && this.getAcceptedFiles().length >= this.options.maxFiles) {
                done(this.options.dictMaxFilesExceeded.replace("{{maxFiles}}", this.options.maxFiles));
                this.emit("maxfilesexceeded", file);
            } else {
                this.options.accept.call(this, file, done);
            }
        }
    }, {
        key: "addFile",
        value: function addFile(file) {
            var _this7 = this;
            file.upload = {
                uuid: Dropzone.uuidv4(),
                progress: 0,
                total: file.size,
                bytesSent: 0,
                filename: this._renameFile(file)
            };
            this.files.push(file);
            file.status = Dropzone.ADDED;
            this.emit("addedfile", file);
            this._enqueueThumbnail(file);
            this.accept(file, function(error) {
                if (error) {
                    file.accepted = false;
                    _this7._errorProcessing([file], error);
                } else {
                    file.accepted = true;
                    if (_this7.options.autoQueue) {
                        _this7.enqueueFile(file);
                    }
                }
                _this7._updateMaxFilesReachedClass();
            });
        }
    }, {
        key: "enqueueFiles",
        value: function enqueueFiles(files) {
            var _iterator16 = _createForOfIteratorHelper(files),
                _step16;
            try {
                for (_iterator16.s(); !(_step16 = _iterator16.n()).done;) {
                    var file = _step16.value;
                    this.enqueueFile(file);
                }
            } catch (err) {
                _iterator16.e(err);
            } finally {
                _iterator16.f();
            }
            return null;
        }
    }, {
        key: "enqueueFile",
        value: function enqueueFile(file) {
            var _this8 = this;
            if (file.status === Dropzone.ADDED && file.accepted === true) {
                file.status = Dropzone.QUEUED;
                if (this.options.autoProcessQueue) {
                    return setTimeout(function() {
                        return _this8.processQueue();
                    }, 0);
                }
            } else {
                throw new Error("This file can't be queued because it has already been processed or was rejected.");
            }
        }
    }, {
        key: "_enqueueThumbnail",
        value: function _enqueueThumbnail(file) {
            var _this9 = this;
            if (this.options.createImageThumbnails && file.type.match(/image.*/) && file.size <= this.options.maxThumbnailFilesize * 1024 * 1024) {
                this._thumbnailQueue.push(file);
                return setTimeout(function() {
                    return _this9._processThumbnailQueue();
                }, 0);
            }
        }
    }, {
        key: "_processThumbnailQueue",
        value: function _processThumbnailQueue() {
            var _this10 = this;
            if (this._processingThumbnail || this._thumbnailQueue.length === 0) {
                return;
            }
            this._processingThumbnail = true;
            var file = this._thumbnailQueue.shift();
            return this.createThumbnail(file, this.options.thumbnailWidth, this.options.thumbnailHeight, this.options.thumbnailMethod, true, function(dataUrl) {
                _this10.emit("thumbnail", file, dataUrl);
                _this10._processingThumbnail = false;
                return _this10._processThumbnailQueue();
            });
        }
    }, {
        key: "removeFile",
        value: function removeFile(file) {
            if (file.status === Dropzone.UPLOADING) {
                this.cancelUpload(file);
            }
            this.files = without(this.files, file);
            this.emit("removedfile", file);
            if (this.files.length === 0) {
                return this.emit("reset");
            }
        }
    }, {
        key: "removeAllFiles",
        value: function removeAllFiles(cancelIfNecessary) {
            if (cancelIfNecessary == null) {
                cancelIfNecessary = false;
            }
            var _iterator17 = _createForOfIteratorHelper(this.files.slice()),
                _step17;
            try {
                for (_iterator17.s(); !(_step17 = _iterator17.n()).done;) {
                    var file = _step17.value;
                    if (file.status !== Dropzone.UPLOADING || cancelIfNecessary) {
                        this.removeFile(file);
                    }
                }
            } catch (err) {
                _iterator17.e(err);
            } finally {
                _iterator17.f();
            }
            return null;
        }
    }, {
        key: "resizeImage",
        value: function resizeImage(file, width, height, resizeMethod, callback) {
            var _this11 = this;
            return this.createThumbnail(file, width, height, resizeMethod, true, function(dataUrl, canvas) {
                if (canvas == null) {
                    return callback(file);
                } else {
                    var resizeMimeType = _this11.options.resizeMimeType;
                    if (resizeMimeType == null) {
                        resizeMimeType = file.type;
                    }
                    var resizedDataURL = canvas.toDataURL(resizeMimeType, _this11.options.resizeQuality);
                    if (resizeMimeType === 'image/jpeg' || resizeMimeType === 'image/jpg') {
                        resizedDataURL = ExifRestore.restore(file.dataURL, resizedDataURL);
                    }
                    return callback(Dropzone.dataURItoBlob(resizedDataURL));
                }
            });
        }
    }, {
        key: "createThumbnail",
        value: function createThumbnail(file, width, height, resizeMethod, fixOrientation, callback) {
            var _this12 = this;
            var fileReader = new FileReader();
            fileReader.onload = function() {
                file.dataURL = fileReader.result;
                if (file.type === "image/svg+xml") {
                    if (callback != null) {
                        callback(fileReader.result);
                    }
                    return;
                }
                _this12.createThumbnailFromUrl(file, width, height, resizeMethod, fixOrientation, callback);
            };
            fileReader.readAsDataURL(file);
        }
    }, {
        key: "displayExistingFile",
        value: function displayExistingFile(mockFile, imageUrl, callback, crossOrigin) {
            var _this13 = this;
            var resizeThumbnail = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : true;
            this.emit("addedfile", mockFile);
            this.emit("complete", mockFile);
            if (!resizeThumbnail) {
                this.emit("thumbnail", mockFile, imageUrl);
                if (callback) callback();
            } else {
                var onDone = function onDone(thumbnail) {
                    _this13.emit('thumbnail', mockFile, thumbnail);
                    if (callback) callback();
                };
                mockFile.dataURL = imageUrl;
                this.createThumbnailFromUrl(mockFile, this.options.thumbnailWidth, this.options.thumbnailHeight, this.options.resizeMethod, this.options.fixOrientation, onDone, crossOrigin);
            }
        }
    }, {
        key: "createThumbnailFromUrl",
        value: function createThumbnailFromUrl(file, width, height, resizeMethod, fixOrientation, callback, crossOrigin) {
            var _this14 = this;
            var img = document.createElement("img");
            if (crossOrigin) {
                img.crossOrigin = crossOrigin;
            }
            fixOrientation = getComputedStyle(document.body)['imageOrientation'] == 'from-image' ? false : fixOrientation;
            img.onload = function() {
                var loadExif = function loadExif(callback) {
                    return callback(1);
                };
                if (typeof EXIF !== 'undefined' && EXIF !== null && fixOrientation) {
                    loadExif = function loadExif(callback) {
                        return EXIF.getData(img, function() {
                            return callback(EXIF.getTag(this, 'Orientation'));
                        });
                    };
                }
                return loadExif(function(orientation) {
                    file.width = img.width;
                    file.height = img.height;
                    var resizeInfo = _this14.options.resize.call(_this14, file, width, height, resizeMethod);
                    var canvas = document.createElement("canvas");
                    var ctx = canvas.getContext("2d");
                    canvas.width = resizeInfo.trgWidth;
                    canvas.height = resizeInfo.trgHeight;
                    if (orientation > 4) {
                        canvas.width = resizeInfo.trgHeight;
                        canvas.height = resizeInfo.trgWidth;
                    }
                    switch (orientation) {
                        case 2:
                            ctx.translate(canvas.width, 0);
                            ctx.scale(-1, 1);
                            break;
                        case 3:
                            ctx.translate(canvas.width, canvas.height);
                            ctx.rotate(Math.PI);
                            break;
                        case 4:
                            ctx.translate(0, canvas.height);
                            ctx.scale(1, -1);
                            break;
                        case 5:
                            ctx.rotate(0.5 * Math.PI);
                            ctx.scale(1, -1);
                            break;
                        case 6:
                            ctx.rotate(0.5 * Math.PI);
                            ctx.translate(0, -canvas.width);
                            break;
                        case 7:
                            ctx.rotate(0.5 * Math.PI);
                            ctx.translate(canvas.height, -canvas.width);
                            ctx.scale(-1, 1);
                            break;
                        case 8:
                            ctx.rotate(-0.5 * Math.PI);
                            ctx.translate(-canvas.height, 0);
                            break;
                    }
                    drawImageIOSFix(ctx, img, resizeInfo.srcX != null ? resizeInfo.srcX : 0, resizeInfo.srcY != null ? resizeInfo.srcY : 0, resizeInfo.srcWidth, resizeInfo.srcHeight, resizeInfo.trgX != null ? resizeInfo.trgX : 0, resizeInfo.trgY != null ? resizeInfo.trgY : 0, resizeInfo.trgWidth, resizeInfo.trgHeight);
                    var thumbnail = canvas.toDataURL("image/png");
                    if (callback != null) {
                        return callback(thumbnail, canvas);
                    }
                });
            };
            if (callback != null) {
                img.onerror = callback;
            }
            return img.src = file.dataURL;
        }
    }, {
        key: "processQueue",
        value: function processQueue() {
            var parallelUploads = this.options.parallelUploads;
            var processingLength = this.getUploadingFiles().length;
            var i = processingLength;
            if (processingLength >= parallelUploads) {
                return;
            }
            var queuedFiles = this.getQueuedFiles();
            if (!(queuedFiles.length > 0)) {
                return;
            }
            if (this.options.uploadMultiple) {
                return this.processFiles(queuedFiles.slice(0, parallelUploads - processingLength));
            } else {
                while (i < parallelUploads) {
                    if (!queuedFiles.length) {
                        return;
                    }
                    this.processFile(queuedFiles.shift());
                    i++;
                }
            }
        }
    }, {
        key: "processFile",
        value: function processFile(file) {
            return this.processFiles([file]);
        }
    }, {
        key: "processFiles",
        value: function processFiles(files) {
            var _iterator18 = _createForOfIteratorHelper(files),
                _step18;
            try {
                for (_iterator18.s(); !(_step18 = _iterator18.n()).done;) {
                    var file = _step18.value;
                    file.processing = true;
                    file.status = Dropzone.UPLOADING;
                    this.emit("processing", file);
                }
            } catch (err) {
                _iterator18.e(err);
            } finally {
                _iterator18.f();
            }
            if (this.options.uploadMultiple) {
                this.emit("processingmultiple", files);
            }
            return this.uploadFiles(files);
        }
    }, {
        key: "_getFilesWithXhr",
        value: function _getFilesWithXhr(xhr) {
            var files;
            return files = this.files.filter(function(file) {
                return file.xhr === xhr;
            }).map(function(file) {
                return file;
            });
        }
    }, {
        key: "cancelUpload",
        value: function cancelUpload(file) {
            if (file.status === Dropzone.UPLOADING) {
                var groupedFiles = this._getFilesWithXhr(file.xhr);
                var _iterator19 = _createForOfIteratorHelper(groupedFiles),
                    _step19;
                try {
                    for (_iterator19.s(); !(_step19 = _iterator19.n()).done;) {
                        var groupedFile = _step19.value;
                        groupedFile.status = Dropzone.CANCELED;
                    }
                } catch (err) {
                    _iterator19.e(err);
                } finally {
                    _iterator19.f();
                }
                if (typeof file.xhr !== 'undefined') {
                    file.xhr.abort();
                }
                var _iterator20 = _createForOfIteratorHelper(groupedFiles),
                    _step20;
                try {
                    for (_iterator20.s(); !(_step20 = _iterator20.n()).done;) {
                        var _groupedFile = _step20.value;
                        this.emit("canceled", _groupedFile);
                    }
                } catch (err) {
                    _iterator20.e(err);
                } finally {
                    _iterator20.f();
                }
                if (this.options.uploadMultiple) {
                    this.emit("canceledmultiple", groupedFiles);
                }
            } else if (file.status === Dropzone.ADDED || file.status === Dropzone.QUEUED) {
                file.status = Dropzone.CANCELED;
                this.emit("canceled", file);
                if (this.options.uploadMultiple) {
                    this.emit("canceledmultiple", [file]);
                }
            }
            if (this.options.autoProcessQueue) {
                return this.processQueue();
            }
        }
    }, {
        key: "resolveOption",
        value: function resolveOption(option) {
            if (typeof option === 'function') {
                for (var _len3 = arguments.length, args = new Array(_len3 > 1 ? _len3 - 1 : 0), _key3 = 1; _key3 < _len3; _key3++) {
                    args[_key3 - 1] = arguments[_key3];
                }
                return option.apply(this, args);
            }
            return option;
        }
    }, {
        key: "uploadFile",
        value: function uploadFile(file) {
            return this.uploadFiles([file]);
        }
    }, {
        key: "uploadFiles",
        value: function uploadFiles(files) {
            var _this15 = this;
            this._transformFiles(files, function(transformedFiles) {
                if (_this15.options.chunking) {
                    var transformedFile = transformedFiles[0];
                    files[0].upload.chunked = _this15.options.chunking && (_this15.options.forceChunking || transformedFile.size > _this15.options.chunkSize);
                    files[0].upload.totalChunkCount = Math.ceil(transformedFile.size / _this15.options.chunkSize);
                }
                if (files[0].upload.chunked) {
                    var file = files[0];
                    var _transformedFile = transformedFiles[0];
                    var startedChunkCount = 0;
                    file.upload.chunks = [];
                    var handleNextChunk = function handleNextChunk() {
                        var chunkIndex = 0;
                        while (file.upload.chunks[chunkIndex] !== undefined) {
                            chunkIndex++;
                        }
                        if (chunkIndex >= file.upload.totalChunkCount) return;
                        startedChunkCount++;
                        var start = chunkIndex * _this15.options.chunkSize;
                        var end = Math.min(start + _this15.options.chunkSize, _transformedFile.size);
                        var dataBlock = {
                            name: _this15._getParamName(0),
                            data: _transformedFile.webkitSlice ? _transformedFile.webkitSlice(start, end) : _transformedFile.slice(start, end),
                            filename: file.upload.filename,
                            chunkIndex: chunkIndex
                        };
                        file.upload.chunks[chunkIndex] = {
                            file: file,
                            index: chunkIndex,
                            dataBlock: dataBlock,
                            status: Dropzone.UPLOADING,
                            progress: 0,
                            retries: 0
                        };
                        _this15._uploadData(files, [dataBlock]);
                    };
                    file.upload.finishedChunkUpload = function(chunk) {
                        var allFinished = true;
                        chunk.status = Dropzone.SUCCESS;
                        chunk.dataBlock = null;
                        chunk.xhr = null;
                        for (var i = 0; i < file.upload.totalChunkCount; i++) {
                            if (file.upload.chunks[i] === undefined) {
                                return handleNextChunk();
                            }
                            if (file.upload.chunks[i].status !== Dropzone.SUCCESS) {
                                allFinished = false;
                            }
                        }
                        if (allFinished) {
                            _this15.options.chunksUploaded(file, function() {
                                _this15._finished(files, '', null);
                            });
                        }
                    };
                    if (_this15.options.parallelChunkUploads) {
                        for (var i = 0; i < file.upload.totalChunkCount; i++) {
                            handleNextChunk();
                        }
                    } else {
                        handleNextChunk();
                    }
                } else {
                    var dataBlocks = [];
                    for (var _i3 = 0; _i3 < files.length; _i3++) {
                        dataBlocks[_i3] = {
                            name: _this15._getParamName(_i3),
                            data: transformedFiles[_i3],
                            filename: files[_i3].upload.filename
                        };
                    }
                    _this15._uploadData(files, dataBlocks);
                }
            });
        }
    }, {
        key: "_getChunk",
        value: function _getChunk(file, xhr) {
            for (var i = 0; i < file.upload.totalChunkCount; i++) {
                if (file.upload.chunks[i] !== undefined && file.upload.chunks[i].xhr === xhr) {
                    return file.upload.chunks[i];
                }
            }
        }
    }, {
        key: "_uploadData",
        value: function _uploadData(files, dataBlocks) {
            var _this16 = this;
            var xhr = new XMLHttpRequest();
            var _iterator21 = _createForOfIteratorHelper(files),
                _step21;
            try {
                for (_iterator21.s(); !(_step21 = _iterator21.n()).done;) {
                    var file = _step21.value;
                    file.xhr = xhr;
                }
            } catch (err) {
                _iterator21.e(err);
            } finally {
                _iterator21.f();
            }
            if (files[0].upload.chunked) {
                files[0].upload.chunks[dataBlocks[0].chunkIndex].xhr = xhr;
            }
            var method = this.resolveOption(this.options.method, files);
            var url = this.resolveOption(this.options.url, files);
            xhr.open(method, url, true);
            xhr.timeout = this.resolveOption(this.options.timeout, files);
            xhr.withCredentials = !!this.options.withCredentials;
            xhr.onload = function(e) {
                _this16._finishedUploading(files, xhr, e);
            };
            xhr.ontimeout = function() {
                _this16._handleUploadError(files, xhr, "Request timedout after ".concat(_this16.options.timeout / 1000, " seconds"));
            };
            xhr.onerror = function() {
                _this16._handleUploadError(files, xhr);
            };
            var progressObj = xhr.upload != null ? xhr.upload : xhr;
            progressObj.onprogress = function(e) {
                return _this16._updateFilesUploadProgress(files, xhr, e);
            };
            var headers = {
                "Accept": "application/json",
                "Cache-Control": "no-cache",
                "X-Requested-With": "XMLHttpRequest"
            };
            if (this.options.headers) {
                Dropzone.extend(headers, this.options.headers);
            }
            for (var headerName in headers) {
                var headerValue = headers[headerName];
                if (headerValue) {
                    xhr.setRequestHeader(headerName, headerValue);
                }
            }
            var formData = new FormData();
            if (this.options.params) {
                var additionalParams = this.options.params;
                if (typeof additionalParams === 'function') {
                    additionalParams = additionalParams.call(this, files, xhr, files[0].upload.chunked ? this._getChunk(files[0], xhr) : null);
                }
                for (var key in additionalParams) {
                    var value = additionalParams[key];
                    if (Array.isArray(value)) {
                        for (var i = 0; i < value.length; i++) {
                            formData.append(key, value[i]);
                        }
                    } else {
                        formData.append(key, value);
                    }
                }
            }
            var _iterator22 = _createForOfIteratorHelper(files),
                _step22;
            try {
                for (_iterator22.s(); !(_step22 = _iterator22.n()).done;) {
                    var _file = _step22.value;
                    this.emit("sending", _file, xhr, formData);
                }
            } catch (err) {
                _iterator22.e(err);
            } finally {
                _iterator22.f();
            }
            if (this.options.uploadMultiple) {
                this.emit("sendingmultiple", files, xhr, formData);
            }
            this._addFormElementData(formData);
            for (var _i4 = 0; _i4 < dataBlocks.length; _i4++) {
                var dataBlock = dataBlocks[_i4];
                formData.append(dataBlock.name, dataBlock.data, dataBlock.filename);
            }
            this.submitRequest(xhr, formData, files);
        }
    }, {
        key: "_transformFiles",
        value: function _transformFiles(files, done) {
            var _this17 = this;
            var transformedFiles = [];
            var doneCounter = 0;
            var _loop = function _loop(i) {
                _this17.options.transformFile.call(_this17, files[i], function(transformedFile) {
                    transformedFiles[i] = transformedFile;
                    if (++doneCounter === files.length) {
                        done(transformedFiles);
                    }
                });
            };
            for (var i = 0; i < files.length; i++) {
                _loop(i);
            }
        }
    }, {
        key: "_addFormElementData",
        value: function _addFormElementData(formData) {
            if (this.element.tagName === "FORM") {
                var _iterator23 = _createForOfIteratorHelper(this.element.querySelectorAll("input, textarea, select, button")),
                    _step23;
                try {
                    for (_iterator23.s(); !(_step23 = _iterator23.n()).done;) {
                        var input = _step23.value;
                        var inputName = input.getAttribute("name");
                        var inputType = input.getAttribute("type");
                        if (inputType) inputType = inputType.toLowerCase();
                        if (typeof inputName === 'undefined' || inputName === null) continue;
                        if (input.tagName === "SELECT" && input.hasAttribute("multiple")) {
                            var _iterator24 = _createForOfIteratorHelper(input.options),
                                _step24;
                            try {
                                for (_iterator24.s(); !(_step24 = _iterator24.n()).done;) {
                                    var option = _step24.value;
                                    if (option.selected) {
                                        formData.append(inputName, option.value);
                                    }
                                }
                            } catch (err) {
                                _iterator24.e(err);
                            } finally {
                                _iterator24.f();
                            }
                        } else if (!inputType || inputType !== "checkbox" && inputType !== "radio" || input.checked) {
                            formData.append(inputName, input.value);
                        }
                    }
                } catch (err) {
                    _iterator23.e(err);
                } finally {
                    _iterator23.f();
                }
            }
        }
    }, {
        key: "_updateFilesUploadProgress",
        value: function _updateFilesUploadProgress(files, xhr, e) {
            var progress;
            if (typeof e !== 'undefined') {
                progress = 100 * e.loaded / e.total;
                if (files[0].upload.chunked) {
                    var file = files[0];
                    var chunk = this._getChunk(file, xhr);
                    chunk.progress = progress;
                    chunk.total = e.total;
                    chunk.bytesSent = e.loaded;
                    var fileProgress = 0,
                        fileTotal, fileBytesSent;
                    file.upload.progress = 0;
                    file.upload.total = 0;
                    file.upload.bytesSent = 0;
                    for (var i = 0; i < file.upload.totalChunkCount; i++) {
                        if (file.upload.chunks[i] !== undefined && file.upload.chunks[i].progress !== undefined) {
                            file.upload.progress += file.upload.chunks[i].progress;
                            file.upload.total += file.upload.chunks[i].total;
                            file.upload.bytesSent += file.upload.chunks[i].bytesSent;
                        }
                    }
                    file.upload.progress = file.upload.progress / file.upload.totalChunkCount;
                } else {
                    var _iterator25 = _createForOfIteratorHelper(files),
                        _step25;
                    try {
                        for (_iterator25.s(); !(_step25 = _iterator25.n()).done;) {
                            var _file2 = _step25.value;
                            _file2.upload.progress = progress;
                            _file2.upload.total = e.total;
                            _file2.upload.bytesSent = e.loaded;
                        }
                    } catch (err) {
                        _iterator25.e(err);
                    } finally {
                        _iterator25.f();
                    }
                }
                var _iterator26 = _createForOfIteratorHelper(files),
                    _step26;
                try {
                    for (_iterator26.s(); !(_step26 = _iterator26.n()).done;) {
                        var _file3 = _step26.value;
                        this.emit("uploadprogress", _file3, _file3.upload.progress, _file3.upload.bytesSent);
                    }
                } catch (err) {
                    _iterator26.e(err);
                } finally {
                    _iterator26.f();
                }
            } else {
                var allFilesFinished = true;
                progress = 100;
                var _iterator27 = _createForOfIteratorHelper(files),
                    _step27;
                try {
                    for (_iterator27.s(); !(_step27 = _iterator27.n()).done;) {
                        var _file4 = _step27.value;
                        if (_file4.upload.progress !== 100 || _file4.upload.bytesSent !== _file4.upload.total) {
                            allFilesFinished = false;
                        }
                        _file4.upload.progress = progress;
                        _file4.upload.bytesSent = _file4.upload.total;
                    }
                } catch (err) {
                    _iterator27.e(err);
                } finally {
                    _iterator27.f();
                }
                if (allFilesFinished) {
                    return;
                }
                var _iterator28 = _createForOfIteratorHelper(files),
                    _step28;
                try {
                    for (_iterator28.s(); !(_step28 = _iterator28.n()).done;) {
                        var _file5 = _step28.value;
                        this.emit("uploadprogress", _file5, progress, _file5.upload.bytesSent);
                    }
                } catch (err) {
                    _iterator28.e(err);
                } finally {
                    _iterator28.f();
                }
            }
        }
    }, {
        key: "_finishedUploading",
        value: function _finishedUploading(files, xhr, e) {
            var response;
            if (files[0].status === Dropzone.CANCELED) {
                return;
            }
            if (xhr.readyState !== 4) {
                return;
            }
            if (xhr.responseType !== 'arraybuffer' && xhr.responseType !== 'blob') {
                response = xhr.responseText;
                if (xhr.getResponseHeader("content-type") && ~xhr.getResponseHeader("content-type").indexOf("application/json")) {
                    try {
                        response = JSON.parse(response);
                    } catch (error) {
                        e = error;
                        response = "Invalid JSON response from server.";
                    }
                }
            }
            this._updateFilesUploadProgress(files);
            if (!(200 <= xhr.status && xhr.status < 300)) {
                this._handleUploadError(files, xhr, response);
            } else {
                if (files[0].upload.chunked) {
                    files[0].upload.finishedChunkUpload(this._getChunk(files[0], xhr));
                } else {
                    this._finished(files, response, e);
                }
            }
        }
    }, {
        key: "_handleUploadError",
        value: function _handleUploadError(files, xhr, response) {
            if (files[0].status === Dropzone.CANCELED) {
                return;
            }
            if (files[0].upload.chunked && this.options.retryChunks) {
                var chunk = this._getChunk(files[0], xhr);
                if (chunk.retries++ < this.options.retryChunksLimit) {
                    this._uploadData(files, [chunk.dataBlock]);
                    return;
                } else {
                    console.warn('Retried this chunk too often. Giving up.');
                }
            }
            this._errorProcessing(files, response || this.options.dictResponseError.replace("{{statusCode}}", xhr.status), xhr);
        }
    }, {
        key: "submitRequest",
        value: function submitRequest(xhr, formData, files) {
            xhr.send(formData);
        }
    }, {
        key: "_finished",
        value: function _finished(files, responseText, e) {
            var _iterator29 = _createForOfIteratorHelper(files),
                _step29;
            try {
                for (_iterator29.s(); !(_step29 = _iterator29.n()).done;) {
                    var file = _step29.value;
                    file.status = Dropzone.SUCCESS;
                    this.emit("success", file, responseText, e);
                    this.emit("complete", file);
                }
            } catch (err) {
                _iterator29.e(err);
            } finally {
                _iterator29.f();
            }
            if (this.options.uploadMultiple) {
                this.emit("successmultiple", files, responseText, e);
                this.emit("completemultiple", files);
            }
            if (this.options.autoProcessQueue) {
                return this.processQueue();
            }
        }
    }, {
        key: "_errorProcessing",
        value: function _errorProcessing(files, message, xhr) {
            var _iterator30 = _createForOfIteratorHelper(files),
                _step30;
            try {
                for (_iterator30.s(); !(_step30 = _iterator30.n()).done;) {
                    var file = _step30.value;
                    file.status = Dropzone.ERROR;
                    this.emit("error", file, message, xhr);
                    this.emit("complete", file);
                }
            } catch (err) {
                _iterator30.e(err);
            } finally {
                _iterator30.f();
            }
            if (this.options.uploadMultiple) {
                this.emit("errormultiple", files, message, xhr);
                this.emit("completemultiple", files);
            }
            if (this.options.autoProcessQueue) {
                return this.processQueue();
            }
        }
    }], [{
        key: "uuidv4",
        value: function uuidv4() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0,
                    v = c === 'x' ? r : r & 0x3 | 0x8;
                return v.toString(16);
            });
        }
    }]);
    return Dropzone;
}(Emitter);
Dropzone.initClass();
Dropzone.version = "5.7.2";
Dropzone.options = {};
Dropzone.optionsForElement = function(element) {
    if (element.getAttribute("id")) {
        return Dropzone.options[camelize(element.getAttribute("id"))];
    } else {
        return undefined;
    }
};
Dropzone.instances = [];
Dropzone.forElement = function(element) {
    if (typeof element === "string") {
        element = document.querySelector(element);
    }
    if ((element != null ? element.dropzone : undefined) == null) {
        throw new Error("No Dropzone found for given element. This is probably because you're trying to access it before Dropzone had the time to initialize. Use the `init` option to setup any additional observers on your Dropzone.");
    }
    return element.dropzone;
};
Dropzone.autoDiscover = true;
Dropzone.discover = function() {
    var dropzones;
    if (document.querySelectorAll) {
        dropzones = document.querySelectorAll(".dropzone");
    } else {
        dropzones = [];
        var checkElements = function checkElements(elements) {
            return function() {
                var result = [];
                var _iterator31 = _createForOfIteratorHelper(elements),
                    _step31;
                try {
                    for (_iterator31.s(); !(_step31 = _iterator31.n()).done;) {
                        var el = _step31.value;
                        if (/(^| )dropzone($| )/.test(el.className)) {
                            result.push(dropzones.push(el));
                        } else {
                            result.push(undefined);
                        }
                    }
                } catch (err) {
                    _iterator31.e(err);
                } finally {
                    _iterator31.f();
                }
                return result;
            }();
        };
        checkElements(document.getElementsByTagName("div"));
        checkElements(document.getElementsByTagName("form"));
    }
    return function() {
        var result = [];
        var _iterator32 = _createForOfIteratorHelper(dropzones),
            _step32;
        try {
            for (_iterator32.s(); !(_step32 = _iterator32.n()).done;) {
                var dropzone = _step32.value;
                if (Dropzone.optionsForElement(dropzone) !== false) {
                    result.push(new Dropzone(dropzone));
                } else {
                    result.push(undefined);
                }
            }
        } catch (err) {
            _iterator32.e(err);
        } finally {
            _iterator32.f();
        }
        return result;
    }();
};
Dropzone.blacklistedBrowsers = [/opera.*(Macintosh|Windows Phone).*version\/12/i];
Dropzone.isBrowserSupported = function() {
    var capableBrowser = true;
    if (window.File && window.FileReader && window.FileList && window.Blob && window.FormData && document.querySelector) {
        if (!("classList" in document.createElement("a"))) {
            capableBrowser = false;
        } else {
            var _iterator33 = _createForOfIteratorHelper(Dropzone.blacklistedBrowsers),
                _step33;
            try {
                for (_iterator33.s(); !(_step33 = _iterator33.n()).done;) {
                    var regex = _step33.value;
                    if (regex.test(navigator.userAgent)) {
                        capableBrowser = false;
                        continue;
                    }
                }
            } catch (err) {
                _iterator33.e(err);
            } finally {
                _iterator33.f();
            }
        }
    } else {
        capableBrowser = false;
    }
    return capableBrowser;
};
Dropzone.dataURItoBlob = function(dataURI) {
    var byteString = atob(dataURI.split(',')[1]);
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0, end = byteString.length, asc = 0 <= end; asc ? i <= end : i >= end; asc ? i++ : i--) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], {
        type: mimeString
    });
};
var without = function without(list, rejectedItem) {
    return list.filter(function(item) {
        return item !== rejectedItem;
    }).map(function(item) {
        return item;
    });
};
var camelize = function camelize(str) {
    return str.replace(/[\-_](\w)/g, function(match) {
        return match.charAt(1).toUpperCase();
    });
};
Dropzone.createElement = function(string) {
    var div = document.createElement("div");
    div.innerHTML = string;
    return div.childNodes[0];
};
Dropzone.elementInside = function(element, container) {
    if (element === container) {
        return true;
    }
    while (element = element.parentNode) {
        if (element === container) {
            return true;
        }
    }
    return false;
};
Dropzone.getElement = function(el, name) {
    var element;
    if (typeof el === "string") {
        element = document.querySelector(el);
    } else if (el.nodeType != null) {
        element = el;
    }
    if (element == null) {
        throw new Error("Invalid `".concat(name, "` option provided. Please provide a CSS selector or a plain HTML element."));
    }
    return element;
};
Dropzone.getElements = function(els, name) {
    var el, elements;
    if (els instanceof Array) {
        elements = [];
        try {
            var _iterator34 = _createForOfIteratorHelper(els),
                _step34;
            try {
                for (_iterator34.s(); !(_step34 = _iterator34.n()).done;) {
                    el = _step34.value;
                    elements.push(this.getElement(el, name));
                }
            } catch (err) {
                _iterator34.e(err);
            } finally {
                _iterator34.f();
            }
        } catch (e) {
            elements = null;
        }
    } else if (typeof els === "string") {
        elements = [];
        var _iterator35 = _createForOfIteratorHelper(document.querySelectorAll(els)),
            _step35;
        try {
            for (_iterator35.s(); !(_step35 = _iterator35.n()).done;) {
                el = _step35.value;
                elements.push(el);
            }
        } catch (err) {
            _iterator35.e(err);
        } finally {
            _iterator35.f();
        }
    } else if (els.nodeType != null) {
        elements = [els];
    }
    if (elements == null || !elements.length) {
        throw new Error("Invalid `".concat(name, "` option provided. Please provide a CSS selector, a plain HTML element or a list of those."));
    }
    return elements;
};
Dropzone.confirm = function(question, accepted, rejected) {
    if (window.confirm(question)) {
        return accepted();
    } else if (rejected != null) {
        return rejected();
    }
};
Dropzone.isValidFile = function(file, acceptedFiles) {
    if (!acceptedFiles) {
        return true;
    }
    acceptedFiles = acceptedFiles.split(",");
    var mimeType = file.type;
    var baseMimeType = mimeType.replace(/\/.*$/, "");
    var _iterator36 = _createForOfIteratorHelper(acceptedFiles),
        _step36;
    try {
        for (_iterator36.s(); !(_step36 = _iterator36.n()).done;) {
            var validType = _step36.value;
            validType = validType.trim();
            if (validType.charAt(0) === ".") {
                if (file.name.toLowerCase().indexOf(validType.toLowerCase(), file.name.length - validType.length) !== -1) {
                    return true;
                }
            } else if (/\/\*$/.test(validType)) {
                if (baseMimeType === validType.replace(/\/.*$/, "")) {
                    return true;
                }
            } else {
                if (mimeType === validType) {
                    return true;
                }
            }
        }
    } catch (err) {
        _iterator36.e(err);
    } finally {
        _iterator36.f();
    }
    return false;
};
if (typeof jQuery !== 'undefined' && jQuery !== null) {
    jQuery.fn.dropzone = function(options) {
        return this.each(function() {
            return new Dropzone(this, options);
        });
    };
}
if (typeof module !== 'undefined' && module !== null) {
    module.exports = Dropzone;
} else {
    window.Dropzone = Dropzone;
}
Dropzone.ADDED = "added";
Dropzone.QUEUED = "queued";
Dropzone.ACCEPTED = Dropzone.QUEUED;
Dropzone.UPLOADING = "uploading";
Dropzone.PROCESSING = Dropzone.UPLOADING;
Dropzone.CANCELED = "canceled";
Dropzone.ERROR = "error";
Dropzone.SUCCESS = "success";
var detectVerticalSquash = function detectVerticalSquash(img) {
    var iw = img.naturalWidth;
    var ih = img.naturalHeight;
    var canvas = document.createElement("canvas");
    canvas.width = 1;
    canvas.height = ih;
    var ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0);
    var _ctx$getImageData = ctx.getImageData(1, 0, 1, ih),
        data = _ctx$getImageData.data;
    var sy = 0;
    var ey = ih;
    var py = ih;
    while (py > sy) {
        var alpha = data[(py - 1) * 4 + 3];
        if (alpha === 0) {
            ey = py;
        } else {
            sy = py;
        }
        py = ey + sy >> 1;
    }
    var ratio = py / ih;
    if (ratio === 0) {
        return 1;
    } else {
        return ratio;
    }
};
var drawImageIOSFix = function drawImageIOSFix(ctx, img, sx, sy, sw, sh, dx, dy, dw, dh) {
    var vertSquashRatio = detectVerticalSquash(img);
    return ctx.drawImage(img, sx, sy, sw, sh, dx, dy, dw, dh / vertSquashRatio);
};
var ExifRestore = function() {
    function ExifRestore() {
        _classCallCheck(this, ExifRestore);
    }
    _createClass(ExifRestore, null, [{
        key: "initClass",
        value: function initClass() {
            this.KEY_STR = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
        }
    }, {
        key: "encode64",
        value: function encode64(input) {
            var output = '';
            var chr1 = undefined;
            var chr2 = undefined;
            var chr3 = '';
            var enc1 = undefined;
            var enc2 = undefined;
            var enc3 = undefined;
            var enc4 = '';
            var i = 0;
            while (true) {
                chr1 = input[i++];
                chr2 = input[i++];
                chr3 = input[i++];
                enc1 = chr1 >> 2;
                enc2 = (chr1 & 3) << 4 | chr2 >> 4;
                enc3 = (chr2 & 15) << 2 | chr3 >> 6;
                enc4 = chr3 & 63;
                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }
                output = output + this.KEY_STR.charAt(enc1) + this.KEY_STR.charAt(enc2) + this.KEY_STR.charAt(enc3) + this.KEY_STR.charAt(enc4);
                chr1 = chr2 = chr3 = '';
                enc1 = enc2 = enc3 = enc4 = '';
                if (!(i < input.length)) {
                    break;
                }
            }
            return output;
        }
    }, {
        key: "restore",
        value: function restore(origFileBase64, resizedFileBase64) {
            if (!origFileBase64.match('data:image/jpeg;base64,')) {
                return resizedFileBase64;
            }
            var rawImage = this.decode64(origFileBase64.replace('data:image/jpeg;base64,', ''));
            var segments = this.slice2Segments(rawImage);
            var image = this.exifManipulation(resizedFileBase64, segments);
            return "data:image/jpeg;base64,".concat(this.encode64(image));
        }
    }, {
        key: "exifManipulation",
        value: function exifManipulation(resizedFileBase64, segments) {
            var exifArray = this.getExifArray(segments);
            var newImageArray = this.insertExif(resizedFileBase64, exifArray);
            var aBuffer = new Uint8Array(newImageArray);
            return aBuffer;
        }
    }, {
        key: "getExifArray",
        value: function getExifArray(segments) {
            var seg = undefined;
            var x = 0;
            while (x < segments.length) {
                seg = segments[x];
                if (seg[0] === 255 & seg[1] === 225) {
                    return seg;
                }
                x++;
            }
            return [];
        }
    }, {
        key: "insertExif",
        value: function insertExif(resizedFileBase64, exifArray) {
            var imageData = resizedFileBase64.replace('data:image/jpeg;base64,', '');
            var buf = this.decode64(imageData);
            var separatePoint = buf.indexOf(255, 3);
            var mae = buf.slice(0, separatePoint);
            var ato = buf.slice(separatePoint);
            var array = mae;
            array = array.concat(exifArray);
            array = array.concat(ato);
            return array;
        }
    }, {
        key: "slice2Segments",
        value: function slice2Segments(rawImageArray) {
            var head = 0;
            var segments = [];
            while (true) {
                var length;
                if (rawImageArray[head] === 255 & rawImageArray[head + 1] === 218) {
                    break;
                }
                if (rawImageArray[head] === 255 & rawImageArray[head + 1] === 216) {
                    head += 2;
                } else {
                    length = rawImageArray[head + 2] * 256 + rawImageArray[head + 3];
                    var endPoint = head + length + 2;
                    var seg = rawImageArray.slice(head, endPoint);
                    segments.push(seg);
                    head = endPoint;
                }
                if (head > rawImageArray.length) {
                    break;
                }
            }
            return segments;
        }
    }, {
        key: "decode64",
        value: function decode64(input) {
            var output = '';
            var chr1 = undefined;
            var chr2 = undefined;
            var chr3 = '';
            var enc1 = undefined;
            var enc2 = undefined;
            var enc3 = undefined;
            var enc4 = '';
            var i = 0;
            var buf = [];
            var base64test = /[^A-Za-z0-9\+\/\=]/g;
            if (base64test.exec(input)) {
                console.warn('There were invalid base64 characters in the input text.\nValid base64 characters are A-Z, a-z, 0-9, \'+\', \'/\',and \'=\'\nExpect errors in decoding.');
            }
            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, '');
            while (true) {
                enc1 = this.KEY_STR.indexOf(input.charAt(i++));
                enc2 = this.KEY_STR.indexOf(input.charAt(i++));
                enc3 = this.KEY_STR.indexOf(input.charAt(i++));
                enc4 = this.KEY_STR.indexOf(input.charAt(i++));
                chr1 = enc1 << 2 | enc2 >> 4;
                chr2 = (enc2 & 15) << 4 | enc3 >> 2;
                chr3 = (enc3 & 3) << 6 | enc4;
                buf.push(chr1);
                if (enc3 !== 64) {
                    buf.push(chr2);
                }
                if (enc4 !== 64) {
                    buf.push(chr3);
                }
                chr1 = chr2 = chr3 = '';
                enc1 = enc2 = enc3 = enc4 = '';
                if (!(i < input.length)) {
                    break;
                }
            }
            return buf;
        }
    }]);
    return ExifRestore;
}();
ExifRestore.initClass();
var contentLoaded = function contentLoaded(win, fn) {
    var done = false;
    var top = true;
    var doc = win.document;
    var root = doc.documentElement;
    var add = doc.addEventListener ? "addEventListener" : "attachEvent";
    var rem = doc.addEventListener ? "removeEventListener" : "detachEvent";
    var pre = doc.addEventListener ? "" : "on";
    var init = function init(e) {
        if (e.type === "readystatechange" && doc.readyState !== "complete") {
            return;
        }
        (e.type === "load" ? win : doc)[rem](pre + e.type, init, false);
        if (!done && (done = true)) {
            return fn.call(win, e.type || e);
        }
    };
    var poll = function poll() {
        try {
            root.doScroll("left");
        } catch (e) {
            setTimeout(poll, 50);
            return;
        }
        return init("poll");
    };
    if (doc.readyState !== "complete") {
        if (doc.createEventObject && root.doScroll) {
            try {
                top = !win.frameElement;
            } catch (error) {}
            if (top) {
                poll();
            }
        }
        doc[add](pre + "DOMContentLoaded", init, false);
        doc[add](pre + "readystatechange", init, false);
        return win[add](pre + "load", init, false);
    }
};
Dropzone._autoDiscoverFunction = function() {
    if (Dropzone.autoDiscover) {
        return Dropzone.discover();
    }
};
contentLoaded(window, Dropzone._autoDiscoverFunction);

function __guard__(value, transform) {
    return typeof value !== 'undefined' && value !== null ? transform(value) : undefined;
}

function __guardMethod__(obj, methodName, transform) {
    if (typeof obj !== 'undefined' && obj !== null && typeof obj[methodName] === 'function') {
        return transform(obj, methodName);
    } else {
        return undefined;
    }
}