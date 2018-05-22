/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var INPUT_FILE;

var INPUT;

var OUTPUT_FILE;

var STATUS_UPLOAD;

var PROGRESS_UPLOAD;

var options;

$(document).ready(function () {
    options = {
        //target: '#output', // target element(s) to be updated with server response 
        beforeSubmit: beforeSubmit, // pre-submit callback 
        success: afterSuccess, // post-submit callback 
        uploadProgress: OnProgress, //upload progress callback 
        resetForm: true        // reset the form after successful submit 

    };

    $('#MyUploadForm').submit(function () {
        $(this).ajaxSubmit(options);
        // always return false to prevent standard browser submit and page navigation 
        return false;
    });


//function after succesful file upload (when server response)
    function afterSuccess(data)
    {
        $(INPUT_FILE).val(data);
        //alert(base_url_upload);
        $(OUTPUT_FILE).html("<img src='" + base_url_upload + data + "' style='width:50px;'>");

        $('#submit-btn').show(); //hide submit button
        $('#loading-img').hide(); //hide submit button
        $(PROGRESS_UPLOAD).fadeOut(); //hide progress bar

    }

//function to check file size before uploading.
    function beforeSubmit() {

        //check whether browser fully supports all File API
        if (window.File && window.FileReader && window.FileList && window.Blob)
        {

            if (!$(INPUT).val()) //check empty input filed
            {
                $(OUTPUT_FILE).html("Are you kidding me?");
                return false
            }

            var fsize = $(INPUT)[0].files[0].size; //get file size
            var ftype = $(INPUT)[0].files[0].type; // get file type


            //allow file types 
            /* switch (ftype)
             {
             case 'image/png':
             case 'image/gif':
             case 'image/jpeg':
             case 'image/pjpeg':
             case 'text/plain':
             case 'text/html':
             case 'application/x-zip-compressed':
             case 'application/pdf':
             case 'application/msword':
             case 'application/vnd.ms-excel':
             case 'video/mp4':
             break;
             default:
             $("#output").html("<b>" + ftype + "</b> Unsupported file type!");
             return false
             }*/

            //Allowed file size is less than 5 MB (1048576)
            if (fsize > 5242880)
            {
                $(OUTPUT_FILE).html("<b>" + bytesToSize(fsize) + "</b> Too big file! <br />File is too big, it should be less than 5 MB.");
                return false
            }

            $('#submit-btn').hide(); //hide submit button
            $('#loading-img').show(); //hide submit button
            // $("#output").html("");
        } else
        {
            //Output error to older unsupported browsers that doesn't support HTML5 File API
            $(OUTPUT_FILE).html("Please upgrade your browser, because your current browser lacks some new features we need!");
            return false;
        }
    }

//progress bar function
    function OnProgress(event, position, total, percentComplete)
    {
        //Progress bar
        $('#progressbox').show();
        $(PROGRESS_UPLOAD).width(percentComplete + '%') //update progressbar percent complete
        $(STATUS_UPLOAD).html(percentComplete + '%'); //update status text
        if (percentComplete > 50)
        {
            $(STATUS_UPLOAD).css('color', '#000'); //change status text to white after 50%
        }
    }

//function to format bites bit.ly/19yoIPO
    function bytesToSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0)
            return '0 Bytes';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }

});
function save_file(inputf, form, input_save, output_show, status, progress) {

    INPUT = inputf;

    INPUT_FILE = input_save;

    OUTPUT_FILE = output_show;

    STATUS_UPLOAD = status;

    PROGRESS_UPLOAD = progress;

    $(form).submit(function () {

        $(this).ajaxSubmit(options);
        // always return false to prevent standard browser submit and page navigation
        return false;
    });
    $(form).submit();
}