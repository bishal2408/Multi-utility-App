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

// weight unit converter
$("#input_weight_unit").change(function() {
    var inputName = $(this).val();
    var outputName = $('#output_weight_unit').val();
    if(outputName == ''){   
        console.log(inputName);
        $("#convert_to_weight").attr('value', inputName);
    }
        
    else{
        $("#convert_to_weight").attr('value', inputName + '_to_' + outputName);
        var convert_to_weight = $("#convert_to_weight").val();
        var inputValue = $("#input_weight_value").val();
        $.ajax({
            type:'get',
            url:'/weight-converter',
            data:{convert_to_weight:convert_to_weight, input_weight_value: inputValue},
            success:function(data){
                $("#output_weight_value").attr('value', data.conversionValue);
            }
        });
    }
});
$("#output_weight_unit").change(function () {
    var outputName = $(this).val();
    var inputName = $('#input_weight_unit').val();
    if(inputName == '')
        $("#convert_to_weight").attr('value', outputName);
    else{
        $("#convert_to_weight").attr('value', inputName + '_to_' + outputName);
        var convert_to_weight = $("#convert_to_weight").val();
        var inputValue = $("#input_weight_value").val();
       
        $.ajax({
            type:'get',
            url:'/weight-converter',
            data:{convert_to_weight:convert_to_weight, input_weight_value: inputValue},
            success:function(data){
                $("#output_weight_value").attr('value', data.conversionValue);
            }
        });
    }
});
$("#input_weight_value").on("input", function() {
    var inputValue = $(this).val();
    var convert_to_weight = $("#convert_to_weight").val();
    $.ajax({
        type:'get',
        url:'/weight-converter',
        data:{convert_to_weight:convert_to_weight, input_weight_value: inputValue},
        success:function(data){
            $("#output_weight_value").attr('value', data.conversionValue);
        }
    });
});

// temperature unit converter
$("#input_temperature_unit").change(function() {
    var inputName = $(this).val();
    var outputName = $('#output_temperature_unit').val();
    if(outputName == ''){   
        console.log(inputName);
        $("#convert_to_temperature").attr('value', inputName);
    }
        
    else{
        $("#convert_to_temperature").attr('value', inputName + '_to_' + outputName);
        var convert_to_temperature = $("#convert_to_temperature").val();
        var inputValue = $("#input_temperature_value").val();
        $.ajax({
            type:'get',
            url:'/temperature-converter',
            data:{convert_to_temperature:convert_to_temperature, input_temperature_value: inputValue},
            success:function(data){
                $("#output_temperature_value").attr('value', data.conversionValue);
            }
        });
    }
});
$("#output_temperature_unit").change(function () {
    var outputName = $(this).val();
    var inputName = $('#input_temperature_unit').val();
    if(inputName == '')
        $("#convert_to_temperature").attr('value', outputName);
    else{
        $("#convert_to_temperature").attr('value', inputName + '_to_' + outputName);
        var convert_to_temperature = $("#convert_to_temperature").val();
        var inputValue = $("#input_temperature_value").val();
       
        $.ajax({
            type:'get',
            url:'/temperature-converter',
            data:{convert_to_temperature:convert_to_temperature, input_temperature_value: inputValue},
            success:function(data){
                $("#output_temperature_value").attr('value', data.conversionValue);
            }
        });
    }
});
$("#input_temperature_value").on("input", function() {
    var inputValue = $(this).val();
    var convert_to_temperature = $("#convert_to_temperature").val();
    $.ajax({
        type:'get',
        url:'/temperature-converter',
        data:{convert_to_temperature:convert_to_temperature, input_temperature_value: inputValue},
        success:function(data){
            $("#output_temperature_value").attr('value', data.conversionValue);
        }
    });
});