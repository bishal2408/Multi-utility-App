<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Nilambar\NepaliDate\NepaliDate;
use Intervention\Image\Facades\Image;
use PDF;

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
}
