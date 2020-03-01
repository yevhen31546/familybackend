(function ($) {

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            $('#note_photo_id').show();
            $('#note_photo_alt').hide();

            reader.onload = function(e) {
                $('#note_photo_id').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function readUpdateURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            $('#note_photo_id_edit').attr('display', 'block');

            reader.onload = function(e) {
                $('#note_photo_id_edit').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function readAvatarURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            $('#avatar_preview').attr('display', 'block');
            $('#avatar_preview_alt').hide();

            reader.onload = function(e) {
                $('#avatar_preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    var update_cat = $('input[name="update_category"]').val();
    var update_date = $('input[name="note_update_date"]').val();
    // console.log('update flag:', update_date);
    if ((update_cat === '') || (update_date === '')) {
        localStorage.removeItem("edit");
    } else {
        localStorage.setItem("edit", "true");
    }

    if (localStorage.getItem("edit") === null) {
        $('.note_edit').hide();
    } else if(localStorage.getItem("edit") === 'true') {
        $('.note_edit').show();
    }

    $('.activity-group-note-add').click(function (e) {

        $('#myLargeModalLabel').html('Add New Note');

        $('.note-add-modal input[name="mode"]').val('add');


        e.preventDefault();

        var add_date_val = true;
        var add_note_cat_val = true;
        var add_note_media_val = true;

        console.log("here note");
        var category = $('ul[data-select-name="group_category"] li.selected').attr('data-option-value');
        console.log(category);
        var media = $('ul[data-select-name="multimedia"] li.selected').attr('data-option-value');
        console.log(media);
        var note_add_date = $('#group_note_add_form input[name="group_note_add_date"]').val();
        console.log(note_add_date);
        var group_id = $('#add_note_group_id').val();
        console.log(group_id);
        //validation part
        if(note_add_date === '') {
            $('#group_note_add_form input[name="group_note_add_date"]').css("border", "1px solid red");
            add_date_val = false;
        } else {
            $('#group_note_add_form input[name="group_note_add_date"]').css("border", "1px solid white");
            add_date_val = true;
        }
        if(category === "1") {
            $('#group_note_add_form input[name="group_category"]').parent().parent().css("border","1px solid red");//more efficient
            add_note_cat_val = false;
        } else {
            $('#group_note_add_form input[name="group_category"]').parent().parent().css("border","1px solid white");//more efficient
            add_note_cat_val = true;
        }
        if(media == "addmedia") {
            $('#group_note_add_form input[name="multimedia"]').parent().parent().css("border", "1px solid red");
            add_note_media_val = false;
        } else {
            $('#group_note_add_form input[name="multimedia"]').parent().parent().css("border","1px solid white");//more efficient
            add_note_media_val = true;
            if(media == 'text') {
                if(add_date_val && add_note_media_val && add_note_cat_val) {
                    $('.add-note-content').text('Add');
                    $('.note-add-modal').modal('toggle');
                    $('.note-add-modal input[name="cat_id"]').val(category);
                    $('.note-add-modal input[name="note_date"]').val(note_add_date);
                    $('.note-add-modal input[name="note_media"]').val(media);
                    $('.note-add-modal input[name="note_to"]').val(group_id);     // Group id
                    $('.note-add-modal input[name="note_value"]').show();
                    $('.note-add-modal input[name="note_photo"]').hide();
                    $('.note-add-modal input[name="note_video"]').hide();
                    $('.note-add-modal #note_comment').hide();
                    $('.note-add-modal #note_photo_id').hide();
                    $('.note-add-modal #note_photo_alt').hide();
                    // $('.note-add-modal input[name="note_photo"]').show();
                    // $('.note-add-modal input[name="note_video"]').show();
                    $('.note-add-modal input[name="note_photo"]').removeAttr("required");
                    $('.note-add-modal input[name="note_video"]').removeAttr("required");
                    // $('.note-add-modal input[name="note_video"]').hide();
                }
            } else if(media == 'photo') {
                if(add_date_val && add_note_media_val && add_note_cat_val) {
                    $('.add-note-content').text('Upload');
                    $('.note-add-modal').modal('toggle');
                    $('.note-add-modal input[name="cat_id"]').val(category);
                    $('.note-add-modal input[name="note_date"]').val(note_add_date);
                    $('.note-add-modal input[name="note_media"]').val(media);
                    $('.note-add-modal input[name="note_to"]').val(group_id);     // Profile id
                    $('.note-add-modal input[name="note_value"]').hide();
                    $('.note-add-modal input[name="note_photo"]').show();
                    var img_src = $('#note_photo_id').attr('src');
                    if (img_src === '#' || img_src === '') {
                        $('#note_photo_id').hide();
                        $('#note_photo_alt').show();
                    } else {
                        $('#note_photo_id').show();
                        $('#note_photo_alt').hide();
                    }
                    $('.note-add-modal #note_comment').show();
                    $('.note-add-modal input[name="note_video"]').hide();

                    $('.note-add-modal input[name="note_value"]').removeAttr("required");
                    $('.note-add-modal input[name="note_video"]').removeAttr("required");
                }
            } else if(media == 'video') {
                if(add_date_val && add_note_media_val && add_note_cat_val) {
                    $('.add-note-content').text('Add');
                    $('.note-add-modal').modal('toggle');
                    $('.note-add-modal input[name="cat_id"]').val(category);
                    $('.note-add-modal input[name="note_date"]').val(note_add_date);
                    $('.note-add-modal input[name="note_media"]').val(media);
                    $('.note-add-modal input[name="note_to"]').val(group_id);     // Profile id
                    $('.note-add-modal input[name="note_value"]').hide();
                    $('.note-add-modal input[name="note_photo"]').hide();
                    $('.note-add-modal #note_comment').show();
                    $('.note-add-modal #note_photo_alt').hide();
                    $('.note-add-modal #note_photo_id').hide();
                    $('.note-add-modal input[name="note_video"]').show();

                    $('.note-add-modal input[name="note_value"]').removeAttr("required");
                    $('.note-add-modal input[name="note_photo"]').removeAttr("required");
                }
            }
        }
    });

    $('.activity-note-add').click(function (e) {

        $('#myLargeModalLabel').html('Add New Note');

        $('.note-add-modal input[name="mode"]').val('add');


        e.preventDefault();

        var add_date_val = true;
        var add_note_cat_val = true;
        var add_note_media_val = true;

        console.log("here note");
        var category = $('ul[data-select-name="category"] li.selected').attr('data-option-value');
        console.log(category);
        var media = $('ul[data-select-name="multimedia"] li.selected').attr('data-option-value');
        console.log(media);
        var note_add_date = $('#add_note_form input[name="note_add_date"]').val();
        console.log(note_add_date);

        //validation part
        if(note_add_date === '') {
            $('#add_note_form input[name="note_add_date"]').css("border", "1px solid red");
            add_date_val = false;
        } else {
            $('#add_note_form input[name="note_add_date"]').css("border", "1px solid white");
            add_date_val = true;
        }
        if(category === "1") {
            $('#add_note_form input[name="category"]').parent().parent().css("border","1px solid red");//more efficient
            add_note_cat_val = false;
        } else {
            $('#add_note_form input[name="category"]').parent().parent().css("border","1px solid white");//more efficient
            add_note_cat_val = true;
        }
        if(media == "addmedia") {
            $('#add_note_form input[name="multimedia"]').parent().parent().css("border", "1px solid red");
            add_note_media_val = false;
        } else {
            $('#add_note_form input[name="multimedia"]').parent().parent().css("border","1px solid white");//more efficient
            add_note_media_val = true;
            if(media == 'text') {
                if(add_date_val && add_note_media_val && add_note_cat_val) {
                    $('.add-note-content').text('Add');
                    $('.note-add-modal').modal('toggle');
                    $('.note-add-modal input[name="cat_id"]').val(category);
                    $('.note-add-modal input[name="note_date"]').val(note_add_date);
                    $('.note-add-modal input[name="note_media"]').val(media);
                    $('.note-add-modal input[name="note_value"]').show();
                    $('.note-add-modal input[name="note_photo"]').hide();
                    $('.note-add-modal input[name="note_video"]').hide();
                    $('.note-add-modal #note_comment').hide();
                    $('.note-add-modal #note_photo_id').hide();
                    $('.note-add-modal #note_photo_alt').hide();
                    // $('.note-add-modal input[name="note_photo"]').show();
                    // $('.note-add-modal input[name="note_video"]').show();
                    $('.note-add-modal input[name="note_photo"]').removeAttr("required");
                    $('.note-add-modal input[name="note_video"]').removeAttr("required");
                    // $('.note-add-modal input[name="note_video"]').hide();
                }
            } else if(media == 'photo') {
                if(add_date_val && add_note_media_val && add_note_cat_val) {
                    $('.add-note-content').text('Upload');
                    $('.note-add-modal').modal('toggle');
                    $('.note-add-modal input[name="cat_id"]').val(category);
                    $('.note-add-modal input[name="note_date"]').val(note_add_date);
                    $('.note-add-modal input[name="note_media"]').val(media);
                    $('.note-add-modal input[name="note_value"]').hide();
                    var img_src = $('#note_photo_id').attr('src');
                    if (img_src === '#' || img_src === '') {
                        $('#note_photo_id').hide();
                        $('#note_photo_alt').show();
                    } else {
                        $('#note_photo_id').show();
                        $('#note_photo_alt').hide();
                    }
                    $('.note-add-modal #note_comment').show();
                    $('.note-add-modal input[name="note_photo"]').show();

                    $('.note-add-modal input[name="note_video"]').hide();

                    $('.note-add-modal input[name="note_value"]').removeAttr("required");
                    $('.note-add-modal input[name="note_video"]').removeAttr("required");
                }
            } else if(media == 'video') {
                if(add_date_val && add_note_media_val && add_note_cat_val) {
                    $('.add-note-content').text('Add');
                    $('.note-add-modal').modal('toggle');
                    $('.note-add-modal input[name="cat_id"]').val(category);
                    $('.note-add-modal input[name="note_date"]').val(note_add_date);
                    $('.note-add-modal input[name="note_media"]').val(media);
                    $('.note-add-modal input[name="note_value"]').hide();
                    $('.note-add-modal input[name="note_photo"]').hide();
                    $('.note-add-modal #note_comment').show();
                    $('.note-add-modal #note_photo_alt').hide();
                    $('.note-add-modal #note_photo_id').hide();
                    $('.note-add-modal input[name="note_video"]').show();

                    $('.note-add-modal input[name="note_value"]').removeAttr("required");
                    $('.note-add-modal input[name="note_photo"]').removeAttr("required");
                }
            }
        }
    });

    $('.view_note_submit').click(function(e){
        e.preventDefault();

        var view_date_val = true;
        var view_cat_val = true;

        // var view_date = $('#view_note_form ul[data-select-name="note_view_date"] li.selected').attr('data-option-value');
        var view_date = $('#view_note_form input[name="note_view_date"]').val();
        var view_cat = $('#view_note_form ul[data-select-name="view_category"] li.selected').attr('data-option-value');

        console.log("view_date", view_date);
        console.log("view_cat", view_cat);
        if(view_date === '') {
            $('#view_note_form input[name="note_view_date"]').css("border", "1px solid red");
            view_date_val = false;
        } else {
            $('#view_note_form input[name="note_view_date"]').css("border", "1px solid white");
            view_date_val = true;
        }

        if( view_cat === "1") {
            $('#view_note_form ul[data-select-name="view_category"] li.selected').parent().parent().css("border", "1px solid red");
            view_cat_val = false;
        } else {
            $('#view_note_form ul[data-select-name="view_category"] li.selected').parent().parent().css("border", "1px solid white");
            view_cat_val = true;
        }
        if(view_date_val && view_cat_val) {
            $('#view_note_form').submit();
        }
    });

    $('.update_note_submit').click(function (e) {
        e.preventDefault();

        var update_date_val = true;
        var update_cat_val = true;

        // var update_date = $('#update_note_form ul[data-select-name="note_update_date"] li.selected').attr('data-option-value');
        var update_date = $('#update_note_form input[name="note_update_date"]').val();
        var update_cat = $('#update_note_form ul[data-select-name="update_category"] li.selected').attr('data-option-value');

        if(update_date == '') {
            $('#update_note_form input[name="note_update_date"]').css("border", "1px solid red");
            update_date_val = false;
        } else {
            $('#update_note_form input[name="note_update_date"]').css("border", "1px solid white");
            update_date_val = true;
        }

        if( update_cat === '1') {
            $('#update_note_form ul[data-select-name="update_category"] li.selected').parent().parent().css("border", "1px solid red");
            update_cat_val = false;
        } else {
            $('#update_note_form ul[data-select-name="update_category"] li.selected').parent().parent().css("border", "1px solid white");
            update_cat_val = true;
        }
        if(update_date_val && update_cat_val) {
            window.localStorage.setItem('edit', 'true');
            $('#update_note_form').submit();
        }
    });

    $('.note_edit').click(function() {

        var id_media = $(this).attr("id");
        console.log('media id', id_media);
        var id = id_media.split("_")[0];
        var media = id_media.split("_")[2];
        var to = $("#friendAndfamily").val(); // Get Profile Id
        console.log("profile id: ", to);
        console.log('note id', id);
        console.log(media);


        if(media == 'text') {
            $('.update-note-content').text('Update');
            $('.note-update-modal').modal('toggle');
            $('.note-update-modal input[name="note_value"]').show();

            var note_value = $(this).parent().children('p').html().trim();
            $('.note-update-modal input[name="note_value"]').val(note_value);
            $('.note-update-modal input[name="note_media"]').val('text');
            $('.note-update-modal input[name="note_id"]').val(id);
            $('.note-update-modal input[name="note_to"]').val(to);     // Profile id
            $('.note-update-modal #note_photo_id_edit').hide();
            $('.note-update-modal input[name="note_photo"]').hide();
            $('.note-update-modal input[name="note_video"]').hide();
            $('.note-update-modal #note_comment').hide();

            $('.note-update-modal input[name="note_photo"]').removeAttr("required");
            $('.note-update-modal input[name="note_video"]').removeAttr("required");
        } else if(media == 'photo') {
            $('.update-note-content').text('Upload');
            $('.note-update-modal').modal('toggle');
            $('.note-update-modal input[name="note_photo"]').show();
            $('.note-update-modal #note_photo_id').show();
            $('.note-update-modal #note_photo_id_edit').show();

            var note_value = $(this).parent().children('img').attr('src');
            var note_comment = $(this).parent().children('div.comment_content').text().trim();
            $('#note_photo_id_edit').attr('src', note_value);

            $('.note-update-modal input[name="note_media"]').val('photo');
            $('.note-update-modal input[name="note_id"]').val(id);
            $('.note-update-modal input[name="note_to"]').val(to);     // Profile id
            $('.note-update-modal #note_comment').show();
            $('.note-update-modal #note_comment').val(note_comment);

            $('.note-update-modal input[name="note_value"]').hide();
            $('.note-update-modal input[name="note_video"]').hide();
            if (note_value !== '') {
                $('.note-update-modal input[name="update_note_photo"]').val(note_value);
                $('.note-update-modal input[name="note_photo"]').removeAttr("required");
            }
            $('.note-update-modal input[name="note_value"]').removeAttr("required");
            $('.note-update-modal input[name="note_video"]').removeAttr("required");
        } else if(media == 'video') {
            $('.update-note-content').text('Update');
            $('.note-update-modal').modal('toggle');
            $('.note-update-modal input[name="note_video"]').show();
            var note_value = $(this).parent().children().find('.link--url').attr('href');
            var note_comment = $(this).parent().children('div.comment_content').text().trim();
            console.log(note_comment);
            $('.note-update-modal input[name="note_video"]').val(note_value);
            $('.note-update-modal input[name="note_media"]').val('video');
            $('.note-update-modal input[name="note_id"]').val(id);
            $('.note-update-modal input[name="note_to"]').val(to);     // Profile id
            $('.note-update-modal #note_photo_id_edit').hide();
            $('.note-update-modal #note_comment').show();
            $('.note-update-modal #note_comment').val(note_comment);

            $('.note-update-modal input[name="note_value"]').hide();
            $('.note-update-modal input[name="note_photo"]').hide();

            $('.note-update-modal input[name="note_value"]').removeAttr("required");
            $('.note-update-modal input[name="note_photo"]').removeAttr("required");
        }
    });

    $('.cancel_button').click(function () {
        var link = window.location.href;
        window.location.href = link;
        // console.log('cancel!!', link);
    });

    $("#fileToUpload").change(function() {
        readURL(this);
    });

    $("#updateImage").change(function() {
        readUpdateURL(this);
    });

    /*Cover photo and Profile photo edit Start*/
    $('#cover_photo_id').click(function(e){
        e.preventDefault();

        console.log("here is cover photo edit");
        $('.profile-cover-modal').modal('toggle');
    });

    $('#avatar_edit_btn').click(function(e){
        e.preventDefault();

        var img_src = $('#avatar_preview').attr('src');
        if (img_src === '#' || img_src === '') {
            $('#avatar_preview').attr('display', 'none');
            $('#avatar_preview_alt').show();
        }

        console.log("here is avatar edit");
        $('.avatar-upload-modal').modal('toggle');
    });

    $("#avatarToUpload").change(function() {
        readAvatarURL(this);
    });

    $(document).ready(function(){
        MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

        var trackChange = function(element) {
        var observer = new MutationObserver(function(mutations, observer) {
            if(mutations[0].attributeName == "value") {
                $(element).trigger("change");
            }
            console.log($("#groupfilterform")[0]);
            $("#groupfilterform")[0].submit();
        });
        observer.observe(element, {
            attributes: true
        });
        }

        // Just pass an element to the function to start tracking
        trackChange($('input[name="groupfilter"]').get(0));
    });

})(jQuery);
