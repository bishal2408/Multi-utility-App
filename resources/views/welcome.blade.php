<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Ramailo app</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
       <!-- Nepali Datepicker -->
       <link href="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/css/nepali.datepicker.v4.0.min.css" rel="stylesheet" type="text/css"/>
    </head>
<body>
    <div class="p-5 w-50">
        <div class="qr-generator">
            <h3>Qr Generator</h3>
            <input type="text" name="qr_string" id="qr_string" class="form-control" placeholder="Enter something to generate qr" onsubmit="getQrCode()">
            <button onclick="getQrCode()" class="btn btn-primary my-4">Generate Qr</button>
            <a id="qr-code" target="_blank">
                <img src="" id="qr-image" alt="qr-image" style="display: none;" download>
            </a>
        </div>
        <div class="date-convertor">
            <h3>Date Converter</h3>
            <div class="nepali-to-english">
                <label for="nepali-datepicker">Nepali date</label>
                <input type="text" name="nepali_date" class="form-control" id="nepali-datepicker" placeholder="Select Nepali Date"/>
                <button onclick="getEnglishDate()" class="btn btn-primary my-4">Convert to english date</button>
                <p id="english_date"></p>
            </div>

            <div class="english-to-nepali">
                <label for="english-datepicker">English date</label>
                <input type="date" name="english_date" class="form-control" id="english-datepicker" placeholder="Select English Date" />
                <button onclick="getNepaliDate()" class="btn btn-primary my-4">Convert to Nepali date</button>
                <p id="nepali_date"></p>
            </div>
        </div>

        <div class="password-generator">
            <h3>Password Generator</h3>
            <div class="password-section d-flex column align-items-center">
                <button onclick="getRandomPassword()" class="btn btn-primary my-4">Click to generate a password</button>
                <p id="random_password" class="mx-4"></p>
            </div>
        </div>

        <div class="image-compressor">
            <h3>Image Compressor</h3>
            <form id="myform" action="{{ route('image.compressor') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" name="image" class="form-control">
                <button type="submit" name="submitBtn" class="btn btn-primary my-4">Compress Image</button>
            </form>
            <img src="" id="compressed-image" alt="compressed-image" style="display: none;">
        </div>

        <div class="image-format-converter">
            <h3>Image Converter</h3>
            <form id="convertImageForm" action="{{ route('image.convert') }}" method="post" enctype="multipart/form-data">
                @csrf
                <label for="original_format_image">Upload Image</label>
                <input type="file" id="original_format_image" name="original_format_image" class="form-control">
                <label for="image_format">Convert To</label>
                <select name="image_format" id="image_format" class="form-control" required>
                    <option value="">Select image to format to convert</option>
                    <option value="jpg">jpg</option>
                    <option value="jpeg">jpeg</option>
                    <option value="png">png</option>
                    <option value="webp">webp</option>
                </select>
                <button type="submit" class="btn btn-primary my-4">Convert Image</button>
            </form>
            <img src="" width="500" height="500" id="converted-image" alt="converted-image" style="display: none;">
        </div>

        <div class="file-converter">
            <div class="word-to-pdf">
                <h3>Word to PDF converter</h3>
                <form id="wordToPdfForm" action="{{ route('word.to.pdf') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="word_to_file"></label>
                    <input type="file" name="word_to_pdf" id="word_to_pdf" class="form-control">
                    <input type="submit" class="btn btn-primary my-4" value="Convert to pdf">
                </form>
                <a href="" id="pdf-file" style="display: none;" download="" target="_blank"></a>
            </div>
        </div>

        <div class="unit-converter">
            <h3>Unit Converter</h3>
            <div class="d-flex w-100">
                <div class="d-flex flex-column w-50">
                    <input type="number" name="input_value" id="input_value" class="form-control my-3" placeholder="Input">
                    <select id="input_unit" class="form-control">
                        <option value="">--Select unit--</option>
                        <option value="km">Kilometer</option>
                        <option value="m">Meter</option>
                        <option value="cm">Centimeter</option>
                        <option value="mm">Millimeter</option>
                        <option value="mile">Mile</option>
                        <option value="yard">Yard</option>
                        <option value="foot">Foot</option>
                        <option value="inches">Inches</option>
                    </select>
                </div>
                <h1 class="mx-4">=</h1>
                <div class="d-flex flex-column w-50">
                    <input type="number" name="output_value" id="output_value" class="form-control my-3" placeholder="Output">
                    <select id="output_unit" class="form-control">
                        <option value="">--Select unit--</option>
                        <option value="km">Kilometer</option>
                        <option value="m">Meter</option>
                        <option value="cm">Centimeter</option>
                        <option value="mm">Millimeter</option>
                        <option value="mile">Mile</option>
                        <option value="yard">Yard</option>
                        <option value="foot">Foot</option>
                        <option value="inches">Inches</option>
                    </select>
                </div>
                <input type="hidden" id="convert_to" name="convert_to" value="">
            </div>

        </div>
    </div>
    <script src="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/js/nepali.datepicker.v4.0.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        // load nepali date picker js plugin
        window.onload = function() {
            var mainInput = document.getElementById("nepali-datepicker");
            mainInput.nepaliDatePicker();
        };
        // trigger onclick event whenever you press enter
        $('input[type=text]').each(function(){
            $(this).on("keyup", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    $(this).next().click();
                }
            });
        });
        function getQrCode()
        {
            var qr_string = $('#qr_string').val();
            $.ajax({
                type: 'get',
                url: '/qr-generator',
                data: {qr_string: qr_string},
                success:function(data){
                    $('#qr-image').attr('src', data.qrCodeUrl);
                    $('#qr-image').css('display', 'block');
                    $('#qr-code').attr('href', data.qrCodeUrl);
                }
            });
        }
        function getEnglishDate(){
            var nepali_date = $('#nepali-datepicker').val();
            $.ajax({
                type:'get',
                url:'/nepali-to-english',
                data:{nepali_date:nepali_date},
                success:function(data){
                    $('#english_date').html(data.english_date['year'] + '-' + data.english_date['month'] + '-' + data.english_date['day'] + ' A.D');
                }
            });
        }
        function getNepaliDate(){
            var english_date = $('#english-datepicker').val();
            $.ajax({
                type:'get',
                url:'/english-to-nepali',
                data:{english_date:english_date},
                success:function(data){
                    $('#nepali_date').html(data.nepali_date['year'] + '-' + data.nepali_date['month'] + '-' + data.nepali_date['day'] + ' B.S');
                }
            });
        }
        function getRandomPassword(){
            $.ajax({
                type:'get',
                url:'/random-password',
                success:function(data){
                    $('#random_password').html(data.random_password);
                }
            });
        }
        // get the compressed image
        $(document).on('submit','#myform', function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: $(this).attr('action'),
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                contentType: false,
                processData: false,
                success:function(data){
                    $('#compressed-image').attr('src', "{{ asset('uploads/compressed-images/')}}"+ '/' + data.compressed_image);
                    $('#compressed-image').css('display', 'block');
                    console.log(data.compressed_image);
                }
            });
        });
        // get requested converted image
        $(document).on('submit','#convertImageForm', function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: $(this).attr('action'),
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                contentType: false,
                processData: false,
                success:function(data){
                    $('#converted-image').attr('src', "{{ asset('uploads/converted-images/')}}"+ '/' + data.converted_image);
                    $('#converted-image').css('display', 'block');
                }
            });
        });
        // get word to pdf file
        $(document).on('submit','#wordToPdfForm', function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type:'POST',
                url: $(this).attr('action'),
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                contentType: false,
                processData: false,
                success:function(data){
                    $('#pdf-file').attr({
                    });
                    let link_name = "<span> Download " + data.pdf_file + "</span>";
                    $('#pdf-file').html(link_name);
                    $('#pdf-file').css('display', 'block');
                }
            });
        });

        // for unit conversion
        $("#input_unit").change(function() {
            var inputName = $(this).val();
            var outputName = $('#output_unit').val();
            if(outputName == '')
                $("#convert_to").attr('value', inputName);
            else{
                $("#convert_to").attr('value', inputName + '_to_' + outputName);
                var convert_to = $("#convert_to").val();
                var inputValue = $("#input_value").val();
                $.ajax({
                    type:'get',
                    url:'/unit-converter',
                    data:{convert_to:convert_to, input_value: inputValue},
                    success:function(data){
                        $("#output_value").attr('value', data.conversionValue);
                    }
                });
            }
        });

        $("#output_unit").change(function () {
            var outputName = $(this).val();
            var inputName = $('#input_unit').val();
            if(inputName == '')
                $("#convert_to").attr('value', outputName);
            else{
                $("#convert_to").attr('value', inputName + '_to_' + outputName);
                var convert_to = $("#convert_to").val();
                var inputValue = $("#input_value").val();
               
                $.ajax({
                    type:'get',
                    url:'/unit-converter',
                    data:{convert_to:convert_to, input_value: inputValue},
                    success:function(data){
                        $("#output_value").attr('value', data.conversionValue);

                    }
                });
            }
        });

        $("#input_value").on("input", function() {
            var inputValue = $(this).val();
            var convert_to = $("#convert_to").val();
            $.ajax({
                type:'get',
                url:'/unit-converter',
                data:{convert_to:convert_to, input_value: inputValue},
                success:function(data){
                    $("#output_value").attr('value', data.conversionValue);
                }
            });
        });
    </script>
</body>
</html>