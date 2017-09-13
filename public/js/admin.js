CKEDITOR.env.isCompatible = true;
var $ = jQuery.noConflict();
jQuery(document).ready(function ($) {

    if ($('#post_content').length){
        var config_editor_blog = {
            height : 500,
            /*toolbar : [
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
                { name: 'links', items: [ 'Link', 'Unlink'] },
                { name: 'insert', items: [ 'Image', 'Smiley', 'Youtube' ,'Maximize' ] }
            ],*/
            extraPlugins : 'youtube',
            youtube_width : '580',
            youtube_height : '420',
            youtube_related : true,
            youtube_older : false,
            youtube_privacy : false,
            youtube_autoplay : false
        };
        editor_vi = CKEDITOR.replace('post_content', config_editor_blog);
        CKFinder.setupCKEditor(editor_vi, site_url+'/public/plugins/ckfinder/');
        if( $('#post_content').length ) {
            CKEDITOR.instances.post_content.on('change', function(e) {
                CKEDITOR.instances.post_content.updateElement();
            });
        }
    }


    $('.have-sub').click(function () {
        var obj = $(this);
        var show = obj.attr('data-show');
        if (show && show == '1'){
            obj.closest('li').find('.sub').fadeIn();
            obj.addClass('show');
            obj.attr('data-show','0');
        }else{
            obj.closest('li').find('.sub').fadeOut();
            obj.removeClass('show');
            obj.attr('data-show','1');
        }
    });


    // save category
    $('#btn-add-new-category').click(function () {
        var modal = '#modalSaveCategory';

        $(modal).on('shown.bs.modal', function (){
            var obj = $(this);
            $('.modal-title',obj).text('Add new category');
            //$('input[name="name"]',obj).val(category_id);
            $('form',obj).submit();
        });

        var originalModal = $(modal).clone();
        $(modal).on('hidden.bs.modal', function () {
            $(modal).remove();
            var myClone = originalModal.clone();
            $('body').append(myClone);
        });

        $(modal).modal({
            show: 'true'
        });
    });

    $('.btn-edit-category').click(function (e) {
        var modal = '#modalSaveCategory';
        var id = $(this).attr('data-id');

        $(modal).on('shown.bs.modal', function (){
            var obj = $(this);

            $('.modal-title',obj).text('Edit new category');
            $('input[name="id"]',obj).val(id);
            $.ajax({
                type : "get",
                url : site_url+'/admin/ajax-get-category',
                dataType : 'json',
                data : {
                    id : id
                },
                success: function (data, statusText, xhr) {
                    if (data.status='success'){
                        var  cate = data.data;

                        $('input[name="name"]',obj).val(cate.name);
                        $('textarea[name="description"]',obj).val(cate.description);

                        $('#option-cate-parent-'+cate.id,obj).css('display','none');
                        $('#option-cate-parent-'+cate.parent,obj).attr('selected',true);

                        $('form',obj).submit();
                    }else{
                        alert('An error, please try again.')
                    }
                }
            });
        });

        var originalModal = $(modal).clone();
        $(modal).on('hidden.bs.modal', function () {
            $(modal).remove();
            var myClone = originalModal.clone();
            $('body').append(myClone);
        });

        $(modal).modal({
            show: 'true'
        });
    });

    $('body').delegate('#saveCategory','submit',function (e) {
        e.preventDefault();
        $('#saveCategory').validate({
            rules : {
                name : 'required'
            },
            /*errorPlacement: function (error, element) {
                element.attr('data-original-title', error.text())
                    .attr('data-toggle', 'tooltip')
                    .attr('data-placement', 'top');
                $(element).tooltip('show');
            },
            unhighlight: function (element) {
                $(element)
                    .removeAttr('data-toggle')
                    .removeAttr('data-original-title')
                    .removeAttr('data-placement')
                    .removeClass('error');
                $(element).unbind("tooltip");
            },*/
            submitHandler: function (form) {
                $.ajax({
                    type : "post",
                    url : site_url+'/admin/ajax-save-category',
                    dataType : 'json',
                    data : $(form).serialize(),
                    beforeSend: function () {
                        $('input, button, textarea', $(form)).attr('disabled', true).css('opacity', 0.5);
                    },
                    success: function (data, statusText, xhr) {
                        $('input, button, textarea', $(form)).attr('disabled', false).css('opacity', 1);
                        if (data.status == 'success'){
                            alert(data.message);
                            window.location.reload();
                        }else{
                            alert(data.message);
                        }
                    }
                });
            }
        });
    });


    $('.btn-delete-category').click(function (e) {
       e.preventDefault();
        var modal = '#modalDeleteCategory';
        var id = $(this).attr('data-id');

        $(modal).on('shown.bs.modal', function () {
            var obj = $(this);
            $('input[name="id"]',obj).val(id);
        });

        var originalModal = $(modal).clone();
        $(modal).on('hidden.bs.modal', function () {
            $(modal).remove();
            var myClone = originalModal.clone();
            $('body').append(myClone);
        });

        $(modal).modal({
            show: 'true'
        });
    });

    $('body').delegate('.form-delete-category','submit',function (e) {
        e.preventDefault();
        var obj = $(this);
        var id = $('input[name="id"]',obj).val();

        $.ajax({
            type : "post",
            url : site_url+'/admin/ajax-delete-category',
            dataType : 'json',
            data : obj.serialize(),
            beforeSend: function () {
                $('input, button, textarea', obj).attr('disabled', true).css('opacity', 0.5);
            },
            success: function (data, statusText, xhr) {
                $('input, button, textarea', obj).attr('disabled', false).css('opacity', 1);
                if (data.status == 'success'){
                    alert(data.message);
                    $('#tr-category-'+id).remove();
                    $('#modalDeleteCategory').modal('hide');
                }else{
                    alert(data.message);
                }
            }
        });
    });
    //


    /**
     * Delete post
     */
    $('.btn-delete-post').click(function (e) {
        e.preventDefault();
        var modal = '#modalDeletePost';
        var id = $(this).attr('data-id');

        $(modal).on('shown.bs.modal', function () {
            var obj = $(this);
            $('input[name="id"]',obj).val(id);
        });

        var originalModal = $(modal).clone();
        $(modal).on('hidden.bs.modal', function () {
            $(modal).remove();
            var myClone = originalModal.clone();
            $('body').append(myClone);
        });

        $(modal).modal({
            show: 'true'
        });
    });

    $('body').delegate('.form-delete-post','submit',function (e) {
        e.preventDefault();
        var obj = $(this);
        var id = $('input[name="id"]',obj).val();

        $.ajax({
            type : "post",
            url : site_url+'/admin/ajax-delete-post',
            dataType : 'json',
            data : obj.serialize(),
            beforeSend: function () {
                $('input, button, textarea', obj).attr('disabled', true).css('opacity', 0.5);
            },
            success: function (data, statusText, xhr) {
                $('input, button, textarea', obj).attr('disabled', false).css('opacity', 1);
                if (data.status == 'success'){
                    alert(data.message);
                    $('#tr-post-'+id).remove();
                    $('#modalDeletePost').modal('hide');
                }else{
                    alert(data.message);
                }
            }
        });
    });

    el("featured_image").addEventListener("change", readImage, false);
});
function el(id){return document.getElementById(id);} // Get elem by ID
function readImage() {
    if ( this.files && this.files[0] ) {
        var FR= new FileReader();
        FR.onload = function(e) {
            el("featured_image_result").src = e.target.result;
        };
        FR.readAsDataURL( this.files[0] );
    }
}