jQuery(function () {
    'use strict';
    var $editarea = jQuery('#wiki__text');
    if (!$editarea.length) {
        return;
    }


    /**
     * Create a XMLHttpRequest that updates the value of the provided progressbar
     *
     * @param {JQuery<Node>} $progressBar jQuery object of the progress-bar
     * @return {XMLHttpRequest} the XMLHttpRequest expected by jQuery's .ajax()
     */
    function createXHRudateProgressBar($progressBar) {
        var xhr = jQuery.ajaxSettings.xhr();
        xhr.upload.onprogress = function (ev) {
            if (ev.lengthComputable) {
                var percentComplete = ev.loaded / ev.total;
                $progressBar.progressbar('option', {value: percentComplete});
            }
        };
        return xhr;
    }

    var $errorDialog = null;
    var filesThatExist = [];


    /**
     * Remove the first item from the stack of files
     *
     * @return {void}
     */
    function skipFile() {
        $errorDialog.remove();
        filesThatExist.shift();
        if (filesThatExist.length) {
            showErrorDialog();
        }
    }

    /**
     * Upload and overwrite the first item from the stack of files
     *
     * @return {void}
     */
    function overwriteFile() {
        $errorDialog.remove();
        uploadFiles([filesThatExist.shift()], true);
        if (filesThatExist.length) {
            showErrorDialog();
        }
    }

    /**
     * Upload all remaining files to the server and overwrite the existing files there
     *
     * @return {void}
     */
    function overwriteAll() {
        $errorDialog.remove();
        uploadFiles(filesThatExist, true);
        filesThatExist = [];
    }

    /**
     * Offer to rename the first file in the stack of files
     *
     * @return {void}
     */
    function renameFile() {
        var $newInput = jQuery('<form></form>');
        $newInput.append(jQuery('<input name="filename">').val(filesThatExist[0].name).css('margin-right', '0.4em'));
        $newInput.append(jQuery('<button name="rename" type="submit">' + window.LANG.plugins.dropfiles.rename + '</button>'));
        $newInput.append(jQuery('<button name="cancel">' + window.LANG.plugins.dropfiles.cancel + '</button>'));
        $newInput.find('button').button();
        $newInput.on('submit', function (event) {
            event.preventDefault();
            $errorDialog.remove();
            var fileToBeUploaded = filesThatExist.shift();
            fileToBeUploaded.newFileName = $newInput.find('input').val();
            uploadFiles([fileToBeUploaded]);
            if (filesThatExist.length) {
                showErrorDialog();
            }
        });
        $newInput.find('button[name="cancel"]').click(function (event) {
            event.preventDefault();
            $errorDialog.remove();
            showErrorDialog();
        });
        $errorDialog.parent().find('.ui-dialog-buttonset').html($newInput);
    }

    /**
     * Create an error dialog and add files to it
     *
     * @return {void}
     */
    function showErrorDialog() {
        var fileName = filesThatExist[0].newFileName || filesThatExist[0].name;
        var text = window.LANG.plugins.dropfiles['popup:fileExists'].replace('%s', fileName);
        if (fileName !== filesThatExist[0].name) {
            text += ' ' + window.LANG.plugins.dropfiles['popup:originalName'].replace('%s', filesThatExist[0].name);
        }
        var errorTitle = window.LANG.plugins.dropfiles['title:fileExistsError'];
        $errorDialog = jQuery('<div id="dropfiles_error_dialog" title="' + errorTitle + '"></div>').text(text).appendTo(jQuery('body'));
        jQuery($errorDialog).dialog({
            width: 510,
            buttons: [
                {
                    text: window.LANG.plugins.dropfiles.skip,
                    click: function () {
                        skipFile()
                    }
                },
                {
                    text: window.LANG.plugins.dropfiles.overwrite,
                    click: function () {
                        overwriteFile()
                    }
                },
                {
                    text: window.LANG.plugins.dropfiles.overwriteAll,
                    click: function () {
                        overwriteAll()
                    }
                },
                {
                    text: window.LANG.plugins.dropfiles.rename,
                    click: function () {
                        renameFile()
                    }
                }
            ]
        }).draggable();
        jQuery($errorDialog).dialog('widget').addClass('dropfiles');

    }

    $editarea.on('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();

        // todo: check if user is allowed to upload files here
    });

    $editarea.on('dragenter', function (e) {
        e.preventDefault();
        e.stopPropagation();

        // todo: check if user is allowed to upload files here
    });

    $editarea.on('dragleave', function (/*e*/) {
        // e.preventDefault();
        // e.stopPropagation();
    });


    var sectok = $editarea.closest('form').find('input[name="sectok"]').val();
    var DW_AJAX_URL = window.DOKU_BASE + 'lib/exe/ajax.php';
    var widgetTitle = window.LANG.plugins.dropfiles['title:fileUpload'];
    var $widget = jQuery('<div title="' + widgetTitle + '" id="plugin_dropfiles_uploadwidget"></div>').hide();
    jQuery('body').append($widget);

    /**
     * Insert the syntax to the uploaded file into the page
     *
     * @param {string} fileid the id of the uploaded file as returned by DokuWiki
     *
     * @return {void}
     */
    function insertSyntax(fileid) {
        var syntax = '{{' + fileid + '}}';
        var caretPos = $editarea[0].selectionStart;
        var prefix = $editarea.text().substring(0, caretPos);
        var postfix = $editarea.text().substring(caretPos);
        $editarea.text(prefix + syntax + postfix);
    }

    /**
     *
     * @param {File[]} filelist List of the files to be uploaded
     * @param {boolean} [overwrite] should the files be overwritten at the server?
     *
     * @return {void}
     */
    function uploadFiles(filelist, overwrite) {
        if (typeof overwrite === 'undefined') {
            // noinspection AssignmentToFunctionParameterJS
            overwrite = 0;
        }
        $widget.show().dialog({
            width: 600
        });
        jQuery($widget).dialog('widget').addClass('dropfiles');
        filelist.forEach(function (file) {
            var fileName = file.newFileName || file.name;

            var $statusbar = jQuery('<div class="dropfiles_file_upload_bar"></div>');
            $statusbar.append(jQuery('<span class="filename">').text(fileName));
            var $progressBar = jQuery('<div class="progressbar">').progressbar({max: 1});
            $statusbar.append($progressBar);
            $widget.append($statusbar);
            if (!$widget.dialog('isOpen')) {
                $widget.dialog('open');
            }

            var form = new FormData();
            form.append('qqfile', file, fileName);
            form.append('call', 'dropfiles_mediaupload');
            form.append('sectok', sectok);
            form.append('ns', window.JSINFO.namespace);
            form.append('ow', overwrite);

            var settings = {
                'type': 'POST',
                'data': form,
                'cache': false,
                'processData': false,
                'contentType': false,
                'xhr': function () {
                    return createXHRudateProgressBar($progressBar)
                }
            };

            jQuery.ajax(DW_AJAX_URL, settings)
                .done(
                    function (data) {
                        if (data.success) {
                            $progressBar.find('.ui-progressbar-value').css('background-color', 'green');
                            $statusbar.find('.filename').wrap(jQuery('<a>').attr({'href': data.link, 'target':'_blank'}));
                            if (window.JSINFO.plugins.dropfiles.insertFileLink) {
                                insertSyntax(data.id);
                            }
                            return;
                        }
                        if (data.errorType === 'file exists') {
                            $progressBar.find('.ui-progressbar-value').css('background-color', 'red');
                            filesThatExist.push(file);
                            if (filesThatExist.length === 1) {
                                showErrorDialog();
                            }
                            var $fileExistsErrorMessage = jQuery('<div class="error"></div>');
                            $fileExistsErrorMessage.text(fileName + ': ' + data.error);
                            $fileExistsErrorMessage.insertAfter($statusbar);
                            return;
                        }
                        $progressBar.find('.ui-progressbar-value').css('background-color', 'red');
                        var $errorMessage = jQuery('<div class="error"></div>');
                        $errorMessage.text(fileName + ': ' + data.error);
                        $errorMessage.insertAfter($statusbar);
                    }
                )
                .fail(
                    function (jqXHR, textStatus, errorThrown) {
                        console.log('Class: , Function: fail-callback, Line 110 {jqXHR, textStatus, errorThrown}(): '
                            , {jqXHR: jqXHR, textStatus: textStatus, errorThrown: errorThrown});
                    }
                );
        });
    }

    $editarea.on('drop', function (e) {
        if (!e.originalEvent.dataTransfer || !e.originalEvent.dataTransfer.files.length) {
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        var files = e.originalEvent.dataTransfer.files;

        // todo Dateigrößen, Filetypes
        var filelist = jQuery.makeArray(files);
        if (!filelist.length) {
            return;
        }


        // check filenames etc.
        jQuery.post(DW_AJAX_URL, {
            call: 'dropfiles_checkfiles',
            sectok: sectok,
            ns: window.JSINFO.namespace,
            filenames: filelist.map(function (file) {
                return file.name;
            })
        }).done(function handleCheckFilesResult(json) {
            var data = JSON.parse(json);
            var filesWithoutErrors = filelist.filter(function (file) {
                return data[file.name] === '';
            });
            filesThatExist = filelist.filter(function (file) {
                return data[file.name] === 'file exists';
            });
            var filesWithOtherErrors = filelist.filter(function (file) {
                return data[file.name] && data[file.name] !== 'file exists';
            });

            // show errors / pending files
            if (filesWithoutErrors.length) {
                uploadFiles(filesWithoutErrors);
            }

            if (filesWithOtherErrors.length) {
                filesWithOtherErrors.map(function (file) {
                    var $errorMessage = jQuery('<div class="error"></div>');
                    $errorMessage.text(file.name + ': ' + data[file.name]);
                    jQuery('.content').prepend($errorMessage);
                });
            }

            // upload valid files
            if (filesThatExist.length) {
                showErrorDialog();
            }

        });
    });
});
