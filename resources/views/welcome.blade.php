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
            <h6>Length Converter</h6>
            <div class="d-flex w-100 my-4 unit">
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

            <h6>Weight Converter</h6>
            <div class="d-flex w-100 my-4">
                <div class="d-flex flex-column w-50">
                    <input type="number" name="input_weight_value" id="input_weight_value" class="form-control my-3" placeholder="Input">
                    <select id="input_weight_unit" class="form-control">
                        <option value="">--Select unit--</option>
                        <option value="tonne">Tonne</option>
                        <option value="kg">Kilogram</option>
                        <option value="g">Gram</option>
                        <option value="mg">Milligram</option>
                        <option value="mcg  ">Microgram</option>
                        <option value="pound">Pound</option>
                        <option value="ounce">Ounce</option>
                        <option value="stone">Stone</option>
                    </select>
                </div>
                <h1 class="mx-4">=</h1>
                <div class="d-flex flex-column w-50">
                    <input type="number" name="output_weight_value" id="output_weight_value" class="form-control my-3" placeholder="Output">
                    <select id="output_weight_unit" class="form-control">
                        <option value="">--Select unit--</option>
                        <option value="tonne">Tonne</option>
                        <option value="kg">Kilogram</option>
                        <option value="g">Gram</option>
                        <option value="mg">Milligram</option>
                        <option value="mcg  ">Microgram</option>
                        <option value="pound">Pound</option>
                        <option value="ounce">Ounce</option>
                        <option value="stone">Stone</option>
                    </select>
                </div>
                <input type="hidden" id="convert_to_weight" name="convert_to_weight" value="">
            </div>

            <h6>Temperature Converter</h6>
            <div class="d-flex w-100 my-4">
                <div class="d-flex flex-column w-50">
                    <input type="number" name="input_temperature_value" id="input_temperature_value" class="form-control my-3" placeholder="Input">
                    <select id="input_temperature_unit" class="form-control">
                        <option value="">--Select unit--</option>
                        <option value="C">Celsius</option>
                        <option value="F">Fahrenheit</option>
                        <option value="K">Kelvin</option>
                    </select>
                </div>
                <h1 class="mx-4">=</h1>
                <div class="d-flex flex-column w-50">
                    <input type="number" name="output_temperature_value" id="output_temperature_value" class="form-control my-3" placeholder="Output">
                    <select id="output_temperature_unit" class="form-control">
                        <option value="">--Select unit--</option>
                        <option value="C">Celsius</option>
                        <option value="F">Fahrenheit</option>
                        <option value="K">Kelvin</option>
                    </select>
                </div>
                <input type="hidden" id="convert_to_temperature" name="convert_to_temperature" value="">
            </div>

            <h6>Liquid Converter</h6>
            <div class="d-flex w-100 my-4">
                <div class="d-flex flex-column w-50">
                    <input type="number" name="input_liquid_value" id="input_liquid_value" class="form-control my-3" placeholder="Input">
                    <select id="input_liquid_unit" class="form-control">
                        <option value="">--Select unit--</option>
                        <option value="liter">Liter</option>
                        <option value="milliliter">Milliliter</option>
                        <option value="cubicfoot">Cubic Foot</option>
                        <option value="cubicinch">Cubic Inch</option>
                        <option value="cubicmeter">Cubic Meter</option>
                        <option value="fluidounce">Fluid Ounce</option>
                        <option value="ustablespoon">US Tablespoon</option>
                        <option value="usteaspoon">US Teaspoon</option>
                        <option value="uscup">US Cup</option>
                    </select>
                </div>
                <h1 class="mx-4">=</h1>
                <div class="d-flex flex-column w-50">
                    <input type="number" name="output_liquid_value" id="output_liquid_value" class="form-control my-3" placeholder="Output">
                    <select id="output_liquid_unit" class="form-control">
                        <option value="">--Select unit--</option>
                        <option value="liter">Liter</option>
                        <option value="milliliter">Milliliter</option>
                        <option value="cubicfoot">Cubic Foot</option>
                        <option value="cubicinch">Cubic Inch</option>
                        <option value="cubicmeter">Cubic Meter</option>
                        <option value="fluidounce">Fluid Ounce</option>
                        <option value="ustablespoon">US Tablespoon</option>
                        <option value="usteaspoon">US Teaspoon</option>
                        <option value="uscup">US Cup</option>
                    </select>
                </div>
                <input type="hidden" id="convert_to_liquid" name="convert_to_liquid" value="">
            </div>
        </div>

        <div class="youtube-video-downlaoder">
            <h6>YouTube Video Downloader</h6>
            <form method="post" action="{{ route('prepare') }}">
                @csrf
        
                @if(Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif
        
                <div class="form-group">
                    <input name="url" type="text" required class="form-control @error('url')  is-invalid @enderror" id="url"
                           aria-describedby="url" value="{{ old('url') }}"
                           autocomplete="off" autofocus>
        
                    @error('url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
        
                <div class="text-center">
                    <button class="btn btn-lg btn-primary">Download</button>
                </div>
            </form>
        </div>
    </div>
    <script src="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/js/nepali.datepicker.v4.0.min.js" type="text/javascript"></script>
    <script src={{ asset('js/index.js') }}></script>
</body>
</html>