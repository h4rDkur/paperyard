<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// symfony process for running sub-process.
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\PhpProcess;
// storage/file facade
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use DB;
use Spatie\PdfToImage\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Image;

class ocr_force extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ocr:force';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

            // run ocrmypdf less than current time();
            $time = time();
            $documents = DB::table('documents')->where([
                ['t_process', '<=', $time],
                ['process_status', '=', 'failed']
            ])->get();

            // iF DOCUMENTS FOUND RUN
            if(count($documents)>=1){

                    // UPDATE FOUND DOCUMENTS STATUS TO PROCESSING.
                    $upd_docz = [];
                    foreach($documents as $upd){
                      array_push($upd_docz, $upd->t_process);
                    }
                    DB::table('documents')
                      ->whereIn('t_process', $upd_docz)
                      ->update(['process_status' => 'rerun_failed']);

                    // APPLY OCR ON EACH DOCUMENT
                    foreach($documents as $key=>$d){

                          //location of original doc.
                          $document_src[$key] = "public/static/documents_new/" . $d->doc_org;
                          //location for where the document with applied ocr will be stored.
                          $document_dst[$key] = "public/static/documents_ocred/" . $d->doc_ocr;
                          //location of processing documents
                          $document_prc[$key] = "public/static/documents_processing/" . $d->doc_prc;
                          //decrypted doc
                          $decrypted_doc = "decrypted-" . $d->doc_org;
                          $decrypted_document_src[$key] = "public/static/documents_new/" . $decrypted_doc;

                          //flags for ocrmypdf. flags are available ata ocrmypdf documentation.
                          $params1[$key] = "ocrmypdf --output-type pdf -l deu+eng --tesseract-timeout 3600 --skip-big 5000 --force-ocr --rotate-pages --deskew";
                           //img2pdf flags
                          $params2[$key] = "img2pdf --output";
                          //qpdf dycrpy flag
                          $params3[$key] = "qpdf --decrypt";

                          //decrypt docs before running ocr
                          //combine ocrmypdf params + source + destination of document. | convert to string
                          //smyfony process accepts only string as parameter.
                          //see symfony process component for more information.
                          $process_ocr[$key] = $params1[$key]     . " " . $decrypted_document_src[$key] . " " . $document_dst[$key];
                          $p_id1[$key] = (string)$process_ocr[$key];
                          //convert image to pdf
                          $process_img[$key] = $params2[$key]     . " " . $document_prc[$key] . " " . $document_src[$key];
                          $p_id2[$key] = (string)$process_img[$key];
                          //run ocr on imgpdf
                          $process_ocr2[$key] = $params1[$key]    . " " . $document_prc[$key] . " " . $document_dst[$key];
                          $p_id3[$key] = (string)$process_ocr2[$key];
                          //decrypt document
                          $process_decrypt[$key] = $params3[$key] . " " . $document_src[$key] . " " . $decrypted_document_src[$key];
                          $p_id4[$key] = (string)$process_decrypt[$key];

                        // PDF
                        if(strtoupper(substr($d->doc_org, -3))=="PDF"){

                            // start decryption process
                            $dec_process[$key] = new Process($p_id4[$key]);
                            $dec_process[$key]->disableOutput();
                            $dec_process[$key]->start();
                            $dec_process[$key]->wait();

                            //check if file is decrypted
                            if(file_exists($decrypted_document_src[$key])){
                                    //run ocr force on decrypted doc
                                    $process[$key] = new Process($p_id1[$key]);
                                    $process[$key]->disableOutput();
                                    $process[$key]->start();
                                    $process[$key]->wait();

                                    if(file_exists('public/static/documents_ocred/'.$d->doc_ocr)){
                                        // file found. no error
                                        DB::table('documents')->where([
                                           ['doc_org', '=', $d->doc_org]
                                        ])->update(['process_status'=>'ocred']);
                                    }else{
                                        //file not found error occured
                                        DB::table('documents')->where([
                                           ['doc_org', '=', $d->doc_org]
                                        ])->update(['process_status'=>'failed_force']);
                                    }
                            }
                            else{
                                DB::table('documents')->where([
                                   ['doc_org', '=', $d->doc_org]
                                ])->update(['process_status'=>'failed_decrypting']);
                                echo "failed decrypting";
                            }

                        }
                        // IMAGE
                        else {
                            //convert images to pdf
                            $process[$key] = new Process($p_id2[$key]);
                            $process[$key]->disableOutput();
                            $process[$key]->start();
                            //code will wait until images are converted to pdf.
                            $process[$key]->wait();
                            //apply ocr
                            $process2[$key] = new Process($p_id3[$key]);
                            $process2[$key]->disableOutput();
                            $process2[$key]->start();
                            $process2[$key]->wait();

                            if(file_exists('public/static/documents_ocred/'.$d->doc_ocr)){
                                // file found. no error
                                DB::table('documents')->where([
                                   ['doc_org', '=', $d->doc_org]
                                ])->update(['process_status'=>'ocred']);
                            }else{
                                //file not found error occured
                                DB::table('documents')->where([
                                   ['doc_org', '=', $d->doc_org]
                                ])->update(['process_status'=>'failed']);
                            }

                        }
                   }

                   echo "success";
              }
    }
}
