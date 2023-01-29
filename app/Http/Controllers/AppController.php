<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Nilambar\NepaliDate\NepaliDate;
use Intervention\Image\Facades\Image;

class AppController extends Controller
{
    public function QrGenerator(Request $request)
    {
        $url = $request->get('qr_string');
        $qrCodeUrl = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=$url&choe=UTF-8";
        if($request->get('qr_string')== null)
        {
            $qrCodeUrl = "";
        }
        return response()->json(['qrCodeUrl'=>$qrCodeUrl]);
    }

    public function getEnglishDate(Request $request)
    {
        $nepali_date = $request->get('nepali_date');
        $date = new Carbon($nepali_date);
        $year = $date->year;
        $month = $date->month;
        $day = $date->day;
        $obj = new NepaliDate();
        $english_date = $obj->convertBsToAd($year, $month, $day);
        return response()->json(['english_date'=>$english_date]);
    }

    public function getNepaliDate(Request $request)
    {
        $english_date = $request->get('english_date');
        $date = new Carbon($english_date);
        $year = $date->year;
        $month = $date->month;
        $day = $date->day;
        $obj = new NepaliDate();
        $english_date = $obj->convertAdToBs($year, $month, $day);
        return response()->json(['nepali_date'=>$english_date]);
    }
    
    public function getRandomPassword()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = substr(str_shuffle($chars), 0, 10);
        return response()->json(['random_password' => $password]);
    }

    public function getCompressedImage(Request $request)
    {
        $actual_file = $request->file('image');
        // check if the file is valid
        if ($actual_file->isValid() && $actual_file->getClientOriginalName()) {
            
            $extension = $actual_file->getClientOriginalExtension();
            $file_name = date('YmdHis') . rand(1, 99999) . '.' . $extension;
            $actual_file->move(public_path('uploads/compressed-images'), $file_name);

            $path = public_path('uploads/compressed-images/') . $file_name;
            
            $image = Image::make($path);

            $image->resize(300, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save();
            return response()->json(['compressed_image' => $file_name]);
        }
        return response()->json(['compressed_image' => null]);
    }

    public function getConvertedImage(Request $request)
    {
        $actual_file = $request->file('original_format_image');
        // check if the file is valid
        if ($actual_file->isValid() && $actual_file->getClientOriginalName()) {
            $extension = $actual_file->getClientOriginalExtension();
            $file_name = date('YmdHis') . rand(1, 99999) . '.' . $extension;
            $actual_file->move(public_path('uploads/converted-images'), $file_name);

            $path = public_path('uploads/converted-images/') . $file_name;
            
            $image = Image::make($path);

            // image original extention, targeted image extension, path to save the image
            $newPath = str_replace($extension , $request->image_format, $path);
    
            $image->save($newPath);
            if ( file_exists($path) && $extension != $request->image_format)
            {
                unlink($path);
            }
            $image_name = str_replace($extension , $request->image_format, $file_name);
            return response()->json(['converted_image' => $image_name]);
        }
        return response()->json(['converted_image' => null]);
    }

    public function convertWordToPdf(Request $request)
    {
        $actual_file = $request->file('word_to_pdf');
        if($actual_file->isValid() && $actual_file->getClientOriginalName())
        {
            $extension = $actual_file->getClientOriginalExtension();
            $file_name = date('YmdHis') . rand(1, 99999) . '.' . $extension;
            $actual_file->move(public_path('uploads/pdf-file'), $file_name);

            $wordPath = public_path('uploads/pdf-file/') . $file_name;
            
            $domPdfPath = base_path('vendor/dompdf/dompdf');
            \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
            \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

            $Content = \PhpOffice\PhpWord\IOFactory::load($wordPath);
            
            $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content,'PDF');

            $PdfPath = str_replace($extension ,'pdf', $wordPath);

            $PDFWriter->save($PdfPath);
            if ( file_exists($wordPath) && $extension != $request->image_format)
            {
                unlink($wordPath);
            }
            $pdf_file = str_replace($extension , 'pdf', $file_name);
            return response()->json(['pdf_file' => $pdf_file]);
        }
        return response()->json(['pdf_file' => null]);
    }

    public function unitConverterLength(Request $request)
    {
        $convertTo = $request->input('convert_to');
        $input_value = $request->input('input_value');
        if($request->get('input_value') == null)
            return response()->json(['conversionValue' => null]);
        $conversionValue = 0;
        switch($convertTo)
        {
            // kilometer to other conversion
            case 'km_to_km':
                $conversionValue = $input_value;
                break;
            case 'km_to_m':
                $conversionValue = $input_value * 1000;
                break;
            case 'km_to_cm':
                $conversionValue = $input_value * 100000;
                break;
            case 'km_to_mm':
                $conversionValue = $input_value * 1000000;
                break;
            case 'km_to_mile':
                $conversionValue = $input_value * 0.621371192;
                break;
            case 'km_to_yard':
                $conversionValue = $input_value * 1093.61;
                break;
            case 'km_to_foot':
                $conversionValue = $input_value * 3280.84;
                break;
            case 'km_to_inches':
                $conversionValue = $input_value * 39370.1;
                break;
            //meter to other conversion
            case 'm_to_km':
                $conversionValue = $input_value * 0.001;
                break;
            case 'm_to_m':
                $conversionValue = $input_value;
                break;
            case 'm_to_cm':
                $conversionValue = $input_value * 100;
                break;
            case 'm_to_mm':
                $conversionValue = $input_value * 1000;
                break;
            case 'm_to_mile':
                $conversionValue = $input_value * 0.000621371;
                break;
            case 'm_to_yard':
                $conversionValue = $input_value * 1.09361;
                break;
            case 'm_to_foot':
                $conversionValue = $input_value * 3.28084;
                break;
            case 'm_to_inches':
                $conversionValue = $input_value * 39.3701;
                break;
            //centimeter to other conversion
            case 'cm_to_km':
                $conversionValue = $input_value * 0.00001;
                break;
            case 'cm_to_m':
                $conversionValue = $input_value * 0.01;
                break;
            case 'cm_to_cm':
                $conversionValue = $input_value;
                break;
            case 'cm_to_mm':
                $conversionValue = $input_value * 10;
                break;
            case 'cm_to_mile':
                $conversionValue = $input_value / 160900;
                break;
            case 'cm_to_yard':
                $conversionValue = $input_value / 91.44;
                break;
            case 'cm_to_foot':
                $conversionValue = $input_value / 30.48;
                break;
            case 'cm_to_inches':
                $conversionValue = $input_value / 2.54;
                break;
            //mm to other conversion
            case 'mm_to_km':
                $conversionValue = $input_value * 1e-6;
                break;
            case 'mm_to_m':
                $conversionValue = $input_value * 0.001;
                break;
            case 'mm_to_cm':
                $conversionValue = $input_value * 0.1;
                break;
            case 'mm_to_mm':
                $conversionValue = $input_value;
                break;
            case 'mm_to_mile':
                $conversionValue = $input_value / 1.609e+6;
                break;
            case 'mm_to_yard':
                $conversionValue = $input_value / 914.4;
                break;
            case 'mm_to_foot':
                $conversionValue = $input_value / 304.8;
                break;
            case 'mm_to_inches':
                $conversionValue = $input_value / 25.4;
                break;
            // mile to other conversion
            case 'mile_to_km':
                $conversionValue = $input_value * 63360;
                break;
            case 'mile_to_m':
                $conversionValue = $input_value * 1609.34;
                break;
            case 'mile_to_cm':
                $conversionValue = $input_value * 160933.999997549;
                break;
            case 'mile_to_mm':
                $conversionValue = $input_value * 1.609e+6;
                break;
            case 'mile_to_mile':
                $conversionValue = $input_value;
                break;
            case 'mile_to_yard':
                $conversionValue = $input_value * 1760;
                break;
            case 'mile_to_foot':
                $conversionValue = $input_value * 5280;
                break;
            case 'mile_to_inches':
                $conversionValue = $input_value * 63360;
                break;
            // yard to other conversion
            case 'yard_to_km':
                $conversionValue = $input_value / 1094;
                break;
            case 'yard_to_m':
                $conversionValue = $input_value / 1.094;
                break;
            case 'yard_to_cm':
                $conversionValue = $input_value * 91.44;
                break;
            case 'yard_to_mm':
                $conversionValue = $input_value * 914.4;
                break;
            case 'yard_to_mile':
                $conversionValue = $input_value / 1760;
                break;
            case 'yard_to_yard':
                $conversionValue = $input_value;
                break;
            case 'yard_to_foot':
                $conversionValue = $input_value * 3;
                break;
            case 'yard_to_inches':
                $conversionValue = $input_value * 36;
                break;
            // foot to other conversion
            case 'foot_to_km':
                $conversionValue = $input_value / 3281;
                break;
            case 'foot_to_m':
                $conversionValue = $input_value / 3.281;
                break;
            case 'foot_to_cm':
                $conversionValue = $input_value * 30.48;
                break;
            case 'foot_to_mm':
                $conversionValue = $input_value * 304.8;
                break;
            case 'foot_to_mile':
                $conversionValue = $input_value / 5280;
                break;
            case 'foot_to_yard':
                $conversionValue = $input_value / 3;
                break;
            case 'foot_to_foot':
                $conversionValue = $input_value;
                break;
            case 'foot_to_inches':
                $conversionValue = $input_value * 12;
                break;
            // inches to other conversions
            case 'inches_to_km':
                $conversionValue = $input_value / 39370;
                break;
            case 'inches_to_m':
                $conversionValue = $input_value / 39.37;
                break;
            case 'inches_to_cm':
                $conversionValue = $input_value * 2.54;
                break;
            case 'inches_to_mm':
                $conversionValue = $input_value * 25.4;
                break;
            case 'inches_to_mile':
                $conversionValue = $input_value / 63360;
                break;
            case 'inches_to_yard':
                $conversionValue = $input_value / 36;
                break;
            case 'inches_to_foot':
                $conversionValue = $input_value / 12;
                break;
            case 'inches_to_inches':
                $conversionValue = $input_value;
                break;
        }
        return response()->json(['conversionValue' => $conversionValue]);
    }

    public function unitConverterWeight(Request $request)
    {
        $convertTo = $request->input('convert_to_weight');
        $input_value = $request->input('input_weight_value');
        if($request->get('input_weight_value') == null)
            return response()->json(['conversionValue' => null]);
        $conversionValue = 0;
        switch ($convertTo) {
            // tonne to other conversions
            case 'tonne_to_kg':
                $conversionValue = $input_value * 1000;
                break;
            case 'tonne_to_g':
                $conversionValue = $input_value * 1000000;
                break;
            case 'tonne_to_mg':
                $conversionValue = $input_value * 1000000000;
                break;
            case 'tonne_to_mcg':
                $conversionValue = $input_value * 1000000000000;
                break;
            case 'tonne_to_stone':
                $conversionValue = $input_value * 157.473;
                break;
            case 'tonne_to_pound':
                $conversionValue = $input_value * 2204.62;
                break;
            case 'tonne_to_ounce':
                $conversionValue = $input_value * 35274;
                break;
            case 'tonne_to_tone':
                $conversionValue = $input_value;
                break;
            // kg to other conversions
            case 'kg_to_kg':
                $conversionValue = $input_value;
                break;
            case 'kg_to_tonne':
                $conversionValue = $input_value / 1000;
                break;
            case 'kg_to_g':
                $conversionValue = $input_value * 1000;
                break;
            case 'kg_to_mg':
                $conversionValue = $input_value * 1000000;
                break;
            case 'kg_to_mcg':
                $conversionValue = $input_value * 1000000000;
                break;
            case 'kg_to_stone':
                $conversionValue = $input_value * 0.15747;
                break;
            case 'kg_to_pound':
                $conversionValue = $input_value * 2.20462;
                break;
            case 'kg_to_ounce':
                $conversionValue = $input_value * 35.274;
                break;
            // gram to other conversions
            case 'g_tog':
                $conversionValue = $input_value;
                break;
            case 'g_to_tonne':
                $conversionValue = $input_value / 1000000;
                break;
            case 'g_to_kg':
                $conversionValue = $input_value / 1000;
                break;
            case 'g_to_mg':
                $conversionValue = $input_value * 1000;
                break;
            case 'g_to_mcg':
                $conversionValue = $input_value * 1000000;
                break;
            case 'g_to_stone':
                $conversionValue = $input_value / 6350.29;
                break;
            case 'g_to_pound':
                $conversionValue = $input_value / 453.592;
                break;
            case 'g_to_ounce':
                $conversionValue = $input_value / 28.35;
                break;
            // milligram to other conversions
            case 'mg_to_mg':
                $conversionValue = $input_value;
                break;      
            case 'mg_to_tonne':
                $conversionValue = $input_value / 1e+9;
                break;
            case 'mg_to_kg':
                $conversionValue = $input_value / 1e+6;
                break;
            case 'mg_to_g':
                $conversionValue = $input_value / 1e+3;
                break;
            case 'mg_to_mcg':
                $conversionValue = $input_value * 1e+3;
                break;
            case 'mg_to_stone':
                $conversionValue = $input_value / 6.35e+9;
                break;
            case 'mg_to_pound':
                $conversionValue = $input_value / 4.535e+9;
                break;
            case 'mg_to_ounce':
                $conversionValue = $input_value / 2.835e+9;
                break;
            // microgram to other conversion
            case 'mcg_to_tonne':
                $conversionValue = $input_value * 1e-12;
                break;
            case 'mcg_to_kg':
                $conversionValue = $input_value * 1e-9;
                break;
            case 'mcg_to_g':
                $conversionValue = $input_value * 1e-6;
                break;
            case 'mcg_to_mg':
                $conversionValue = $input_value * 0.001;
                break;
            case 'mcg_to_mcg':
                $conversionValue = $input_value;
                break;
            case 'mcg_to_stone':
                $conversionValue = $input_value * 6.35029 * 1e-9;
                break;
            case 'mcg_to_pound':
                $conversionValue = $input_value * 2.20462 * 1e-9;
                break;
            case 'mcg_to_ounce':
                $conversionValue = $input_value * 3.527396 * 1e-9;
                break;
            // pound to other conversions
            case 'pound_to_pound':
                $conversionValue = $input_value;
                break;
            case "pound_to_tonne":
                $conversionValue = $input_value * 0.000453592;
                break;
            case "pound_to_kg":
                $conversionValue = $input_value * 0.453592;
                break;
            case "pound_to_g":
                $conversionValue = $input_value * 453.592;
                break;
            case "pound_to_mg":
                $conversionValue = $input_value * 453592;
                break;
            case "pound_to_mcg":
                $conversionValue = $input_value * 453592000;
                break;
            case "pound_to_stone":
                $conversionValue = $input_value * 0.0714286;
                break;
            case "pound_to_ounce":
                $conversionValue = $input_value * 16;
                break;
            // ounce to other conversions
            case 'ounce_to_ounce':
                $conversionValue = $input_value;
                break;
            case 'ounce_to_tonne':
                $conversionValue = $input_value * 0.00003527396;
                break;
            case 'ounce_to_kg':
                $conversionValue = $input_value * 0.0283495;
                break;
            case 'ounce_to_g':
                $conversionValue = $input_value * 28.3495;
                break;
            case 'ounce_to_mg':
                $conversionValue = $input_value * 28349.5;
                break;
            case 'ounce_to_mcg':
                $conversionValue = $input_value * 28349.5e+6;
                break;
            case 'ounce_to_stone':
                $conversionValue = $input_value * 0.00446429;
                break;
            case 'ounce_to_pound':
                $conversionValue = $input_value * 0.0625;
                break;
            // stone to other conversions
            case 'stone_to_stone':
                $conversionValue = $input_value;
                break;
            case 'stone_to_tonne':
                $conversionValue = $input_value * 0.15747;
                break;
            case 'stone_to_kg':
                $conversionValue = $input_value * 6.35029;
                break;
            case 'stone_to_g':
                $conversionValue = $input_value * 6350.29;
                break;
            case 'stone_to_mg':
                $conversionValue = $input_value * 6.35029e+6;
                break;
            case 'stone_to_mcg':
                $conversionValue = $input_value * 6.35029e+9;
                break;
            case 'stone_to_pound':
                $conversionValue = $input_value * 14;
                break;
            case 'stone_to_ounce':
                $conversionValue = $input_value * 224;
                break;

        }
        return response()->json(['conversionValue' => $conversionValue]);
    }
}
