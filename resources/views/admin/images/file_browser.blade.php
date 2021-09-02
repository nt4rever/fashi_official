<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>File Browser</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.16.1/ckeditor.js"
        integrity="sha512-2nFxKVmFuBhAR45DBnAANBjtxzf7z0m6wRU7NOquxibA6efrQpUtdjFT4wzqewqTI3/cCNbBzJNUtu1NxjFiKw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"
        integrity="sha512-RXf+QSDCUQs5uwRKaDoXt55jygZZm2V++WUZduaU/Ui/9EGp3f/2KZVahFZBKGH0s774sd3HmrhUy+SgOFQLVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            var funcNum = <?php echo $_GET['CKEditorFuncNum']; ?>;
            $("#fileExplorer").on('click', 'img', function() {
                var fileURl = $(this).attr('title');
                window.opener.CKEDITOR.tools.callFunction(funcNum, fileURl);
                window.close();
            }).hover(function() {
                $(this).css('cursor', 'pointer');
            });

            $('.delete_image').on('click', function() {
                var path = $(this).data('path');
                var img = $(this).parent().parent().parent();
                $.ajax({
                    type: "GET",
                    cache: "false",
                    url: "{{URL::to('/delete-image-ckeditor')}}",
                    data: {
                        path: path
                    },
                    dataType: "html",
                    success: function(dataRes) {
                        console.log(dataRes)
                        if(dataRes==true){
                            img.remove();
                        }
                    },
                    error: function(error) {
                        Console.log(error)
                        alert("error!")
                    }
                })
                return false;
            });
        });
    </script>
    <style type="text/css">
        ul.file-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        ul.file-list li {
            float: left;
            margin: 5px;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        ul.file-list img {
            display: block;
            margin: 0 auto;
        }

        ul.file-list li:hover {
            background: cornsilk;
            cursor: pointer;
        }

    </style>
</head>

<body>
    <div id="fileExplorer">
        @foreach ($fileNames as $file)
            <div class="thumbnail">
                <ul class="file-list">
                    <li><img src="{{ asset('uploads/ckeditor/' . $file) }}" alt="thumb"
                            title="{{ asset('uploads/ckeditor/' . $file) }}" height="100">
                        <br>
                        <span style="color:darkblue">{{ $file }}</span>
                        <br>
                        <a href="#" class="delete_image" data-path="{{ 'uploads/ckeditor/' . $file }}"><i
                                class="fas fa-trash"></i></a>
                    </li>
                </ul>
            </div>
        @endforeach
    </div>
</body>

</html>
