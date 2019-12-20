var abc = 0; // Declaring and defining global increment variable.
var cnt = 1;
var base = 10;
$(document).ready(function() {
    //  To add new input file field dynamically, on click of "Add More Files" button below function will be executed.
    $('#add_more').click(function() {
        cnt++;
        if(cnt == base) {
          $('#add_more').css("display", "none");
        } else {
          $('#add_more').css("display", "block");
        }
        $(this).before($("<div/>", {
            id: 'filediv'
        }).fadeIn('slow').append($("<input/>", {
            name: 'file[]',
            type: 'file',
            id: 'file'
        }), $("<br/><br/>")));
    });
    // Following function will executes on change event of file input to select different file.
    $('body').on('change', '#file', function() {
        if (this.files && this.files[0]) {
            abc += 1; // Incrementing global variable by 1.
            var z = abc - 1;
            var x = $(this).parent().find('#previewimg' + z).remove();
            $(this).before("<div id='abcd" + abc + "' class='abcd'><img id='previewimg" + abc + "' src=''/></div>");
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
            $(this).hide();
            $("#abcd" + abc).append($("<img/>", {
                id: 'img',
                src: 'img/x.png',
                alt: 'delete'
            }).click(function() {
                $(this).parent().parent().remove();
                cnt -=1;
                if(cnt == base) {
                  $('#add_more').css("display", "none");
                } else {
                  $('#add_more').css("display", "block");
                }
            }));
        }
    });
    // To Preview Image
    function imageIsLoaded(e) {
        $('#previewimg' + abc).attr('src', e.target.result);
    };
    
    
});

////////////////////////////////////////////////////

// function preview_images() {
//     var total_file = document.getElementById("images").files.length;
//     for (var i = 0; i < total_file; i++) {
//         $('#image_preview').append("<div class='col-md-3'><img class='img-responsive' src='" + URL.createObjectURL(event.target.files[i]) + "'></div>");
//     }
// }

// $('#add_more').click(function() {
//     "use strict";
//     $(this).before($("<div/>", {
//         id: 'filediv'
//     }).fadeIn('slow').append(
//         $("<input/>", {
//             name: 'file[]',
//             type: 'file',
//             id: 'file',
//             multiple: 'multiple',
//             accept: 'image/*'
//         })
//     ));
// });

// $('#upload').click(function(e) {
//     "use strict";
//     e.preventDefault();

//     if (window.filesToUpload.length === 0 || typeof window.filesToUpload === "undefined") {
//         alert("No files are selected.");
//         return false;
//     }

//     // Now, upload the files below...
//     // https://developer.mozilla.org/en-US/docs/Using_files_from_web_applications#Handling_the_upload_process_for_a_file.2C_asynchronously
// });

// deletePreview = function(ele, i) {
//     "use strict";
//     try {
//         $(ele).parent().remove();
//         window.filesToUpload.splice(i, 1);
//     } catch (e) {
//         console.log(e.message);
//     }
// }

// $("#file").on('change', function() {
//     "use strict";

//     // create an empty array for the files to reside.
//     window.filesToUpload = [];

//     if (this.files.length >= 1) {
//         $("[id^=previewImg]").remove();
//         $.each(this.files, function(i, img) {
//             var reader = new FileReader(),
//                 newElement = $("<div id='previewImg" + i + "' class='previewBox'><img /></div>"),
//                 deleteBtn = $("<span class='delete' onClick='deletePreview(this, " + i + ")'>X</span>").prependTo(newElement),
//                 preview = newElement.find("img");

//             reader.onloadend = function() {
//                 preview.attr("src", reader.result);
//                 preview.attr("alt", img.name);
//             };

//             try {
//                 window.filesToUpload.push(document.getElementById("file").files[i]);
//             } catch (e) {
//                 console.log(e.message);
//             }

//             if (img) {
//                 reader.readAsDataURL(img);
//             } else {
//                 preview.src = "";
//             }

//             newElement.appendTo("#filediv");
//         });
//     }
// });
///////////////////////////////////////////////////
var input = document.getElementById('images');
var infoArea = document.getElementById('file-upload-filename');

if(input){
//   el.addEventListener('click', swapper, false);
  input.addEventListener('change', showFileName);
}


function showFileName(event) {

    // the change event gives us the input it occurred in 
    var input = event.srcElement;

    // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
    var fileName = input.files[0].name;

    // use fileName however fits your app best, i.e. add it into a div
    infoArea.textContent = 'File name: ' + fileName;
}