@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('active_dashboard', 'p_active_nav')

@section('custom_style')
<style type="text/css" media="screen">
.dashboard_card {
  height:150px;
}

.mg-icon_1 {
   color: #017cff;
   font-size:60px;
   margin-top:20px;
}
.mg-icon_2 {
   color: #b1d5ff;
   font-size:60px;
   margin-top:20px;
}

@media screen and (max-width:500px) {
   .merge_sm_chevron {
       margin-left:-30px !important;
   }
}

.dashboard_card:hover {
  -webkit-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
  -moz-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
  box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
  cursor: pointer;
}

.search_inp {
  text-align:center
}

.cstm_list_g {
  padding-top:20px;
}

.cstm_knob_div {
  padding-left:0px
}

.prev_viewed_doc_div {
  width:110px; height:100%; border-right:3px solid #b1d5ff;
}

.prev_viewd_doc_div_img {
  height:120px !important; border:1px solid #b1d5ff;
}
.prev_viewd_doc_div_img:hover {
  border:1px solid #017cff;;
}

.prev_viewed_docs {
  border: 1px solid #b1d5ff; padding:0px; width:58px; margin:3px
}

.prev_viewed_docs img:hover {
  border: 1px solid #017cff;
}

.prev_viewed_docs_m {
  border: 1px solid #b1d5ff; padding:0px; width:40px; margin:3px
}

.prev_viewed_docs_m img {
  height:60px;
}

.doc_num_stat {
  font-size:15px !important;
  background-color:#017cff !important;
  margin-top:-6px;
}

.doc_num_stat_failed {
  font-size:15px !important;
  background-color:red !important;
  margin-top:-6px;
}


.preload_custm_loc{
   margin-top:-7px;
}

.chart_tbl tr th{
   margin:0px !important;
   padding:0px !important;
}
.d_ac_list{
   cursor: pointer;
   background-color:#b1d5ff;
   color:#000 !important;
}
.d_ac_list:hover{
   cursor: pointer;
   background-color:#017cff !important;
   color:#fff !important;
}

.list-group-autocomplete{
   position:absolute !important;
   z-index:5 !important;

}

.stat_ready {
  color:#017cff;
}
.stat_failed {
  color:red;
}
.cstm_icon_btn {
  padding:2px !important;
  padding-left:5px !important;
  padding-right:5px !important;
  padding-top:0px !important;
  margin-right:7px;
}
.cstm_icon_btn:hover {
  background-color:  #017cff !important;
  -webkit-transition: all .3s;
     -moz-transition: all .3s;
      -ms-transition: all .3s;
       -o-transition: all .3s;
          transition: all .3s;
           color:#fff;
}

/* ------------table custom design -----------------*/

.table-striped>tbody>tr:nth-child(odd)>td,
.table-striped>tbody>tr:nth-child(odd)>th {
   background-color: #fff;
 }

.table-striped>thead>tr:nth-child(odd)>th {
   background-color: #ebedf8;
 }
.table-hover tbody tr:hover td{
   background-color: #b1d5ff !important;
   cursor: pointer;
}

.table-striped>tbody>tr:nth-child(even)>td,
.table-striped>tbody>tr:nth-child(even)>th {
   background-color: #ebedf8;
}
/*---------------------------------------------------*/

/* --------- autocomplete ---------------------------*/
.th-t {
  background-color: #4ddb9f;
}
.th-f {
  background-color: #ef5c8f;
}
.th-ft {
  background-color: #fade45;
}
/*---------------------------------------------------*/

.cstm_input {
  background-color:#ebedf8;
  outline: none;
  border: none !important;
  -webkit-box-shadow: none !important;
  -moz-box-shadow: none !important;
  box-shadow: none !important;
}

.ocr_success {
    color:#017cff;
}

.ocr_failed {
     color:red;
}
</style>
@endsection

@section('content')
<div class="row" ng-controller="dashboard_controller" ng-click="clear_autocomplete()">

  <!-- preloader for search autocomplete -->
  <div class="preloader pl-size-xs" ng-show="searchAutoCompLoader" style="position:absolute; top:102px; right:33px">
    <div class="spinner-layer pl-blue">
      <div class="circle-clipper left">
        <div class="circle"></div>
      </div>
      <div class="circle-clipper right">
        <div class="circle"></div>
      </div>
    </div>
  </div>

  <!-- Search bar -->
  <div>
    <div class="col-md-6  col-md-offset-3">
      <input type="text" class="form-control  input-lg search_inp text-center cstm_input" placeholder="@lang('dashboard.input_search_p_holder')"  ng-model-options='{ debounce: 1000 }' ng-change="onChangeInput()" ng-model="doc_keyword" ng-keydown="searchKeyPress($event)">
      <div class="row cleafix">
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 list-group-autocomplete " >
          <div class="list-group ">
            <!-- AUTCOMPLETE TAGS -->
            <a ng-click="searchDocuments(tag,'tag')" class="list-group-item th-t" ng-repeat="tag in ac_tags track by $index" ng-show="ac_tags!='not_found' && ac_tags!=null">
              <# tag #>
            </a>
            <!-- AUTCOMPLETE FOLDERS  -->
            <a ng-click="searchDocuments(folder.folder_name,'folder')" class="list-group-item  th-f" ng-repeat="folder in ac_folders track by $index" ng-show="ac_folders!='not_found' && ac_folders!=null">
              <# folder.folder_name #>
            </a>
            <!-- AUTCOMPLETE FULLTEXT -->
            <a ng-click="searchDocuments(fulltext,'fulltext')" class="list-group-item  th-ft" ng-repeat="fulltext in ac_fulltext track by $index" ng-show="ac_fulltext!='not_found' && ac_fulltext!=null">
              <# fulltext #>
            </a>
            <!-- NO RESULT FOUND -->
            <a  class="list-group-item  ng-hide" ng-show="no_result_found">
              No result found...
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /Search bar -->

  <!-- Space -->
  <div class="col-md-12">
     <br>
  </div>

  <!-- Dashboard Cards -->
  <div ng-show="dashboard_grid">

          <!-- DOCUMENTS TO EDIT / ARCHIVED -->
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card dashboard_card">
              <div class="body ">
                <ul class="list-group" >
                  <a ng-href="/document/<#oldest_doc.doc_id | default_id #>" style="text-decoration: none !important">
                    <button type="button" class="list-group-item cstm_list_g waves-effect waves-blue"><label>@lang('dashboard.to_edit_tx')</label>
                      <span class="badge bg-light-blue doc_num_stat ng-hide" ng-show="num_to_edit"><# num_edit #></span>
                      <div class="preloader ng-hide pull-right pl-size-xs preload_custm_loc" ng-show="num_data_preloader">
                        <div class="spinner-layer pl-blue">
                          <div class="circle-clipper left">
                            <div class="circle"></div>
                          </div>
                          <div class="circle-clipper right">
                            <div class="circle"></div>
                          </div>
                        </div>
                      </div>
                    </button>
                  </a>
                  <a ng-href="/archives" style="text-decoration: none !important">
                    <button type="button" class="list-group-item cstm_list_g waves-effect waves-blue"><label>@lang('dashboard.This_whole_week_tx')</label>
                      <span class="badge bg-light-blue doc_num_stat ng-hide" ng-show="num_to_archive"><# archive #></span>
                      <div class="preloader ng-hide pull-right pl-size-xs preload_custm_loc" ng-show="num_data_preloader">
                        <div class="spinner-layer pl-blue">
                          <div class="circle-clipper left">
                            <div class="circle"></div>
                          </div>
                          <div class="circle-clipper right">
                            <div class="circle"></div>
                          </div>
                        </div>
                      </div>
                    </button>
                  </a>
                </ul>
              </div>
            </div>
          </div>
          <!-- ./DOCUMENTS TO EDIT / ARCHIVED -->

          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card dashboard_card">
              <div class="body ">
                <ul class="list-group" >
                  <a ng-href="" style="text-decoration: none !important">
                    <button type="button" class="list-group-item cstm_list_g waves-effect waves-blue"><label>Documents being processed</label>
                      <span class="badge bg-light-blue doc_num_stat ng-hide" ng-show="num_to_edit"><# queueDocs #></span>
                      <div class="preloader ng-hide pull-right pl-size-xs preload_custm_loc" ng-show="num_data_preloader">
                        <div class="spinner-layer pl-blue">
                          <div class="circle-clipper left">
                            <div class="circle"></div>
                          </div>
                          <div class="circle-clipper right">
                            <div class="circle"></div>
                          </div>
                        </div>
                      </div>
                    </button>
                  </a>
                  <a href="failed_ocr_documents" style="text-decoration: none !important">
                    <button type="button" class="list-group-item cstm_list_g waves-effect waves-blue"><label>Failed OCRED documents</label>
                      <span class="badge bg-red doc_num_stat ng-hide" ng-show="num_to_archive"><# failedDocs#></span>
                      <div class="preloader ng-hide pull-right pl-size-xs preload_custm_loc" ng-show="num_data_preloader">
                        <div class="spinner-layer pl-blue">
                          <div class="circle-clipper left">
                            <div class="circle"></div>
                          </div>
                          <div class="circle-clipper right">
                            <div class="circle"></div>
                          </div>
                        </div>
                      </div>
                    </button>
                  </a>
                </ul>
              </div>
            </div>
          </div>
          <!-- ./DOCUMENTS TO EDIT / ARCHIVED -->

            <!-- THIS WEEK DOCUMENTS / KNOB-BARCHART -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card dashboard_card">
                <div class="container-fluid " style="padding-top:10px">
                  <div style="width:100%">
                    <table style="width:100% !important; border:0px">
                      <tr>
                        <th rowspan="2">
                          <input type="text" class="knob cstm_knob_div" data-linecap="round" value="{{$knob}}" data-min="0" data-max="200" data-width="130" data-height="130" data-thickness="0.25" data-angleoffset="-180" data-fgColor="#017cff" data-bgColor="#b1d5ff" readonly>
                        </th>
                        <th height="5"><label class="pull-right">diese Woche</label></th>
                      </tr>
                      <tr>
                        <td>
                          <canvas id="myChart" style="width:100% !important; height:78px; padding:0px !important"></canvas>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- LAST OPENED DOCUMENTS -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card dashboard_card">
                <div style="padding:17px">

                  @if(count($latest_opened)>=1)
                  <div class="col-xs-5 col-sm-3 " style="padding-left:0px;">
                    <div class="prev_viewed_doc_div" onclick="window.location='{{ url('document/'.$latest_opened->view_doc_id) }}'">
                      <img src="{{ url('/files/image') .'/'. $latest_opened->thumbnail }}" class="img-responsive prev_viewd_doc_div_img">
                    </div>
                  </div>
                  @endif

                  <!-- Last opened desktop  -->
                  <div class="col-xs-7 col-sm-9 hidden-xs hidden-sm hidden-md">
                    @if(count($last_opened)>=1)
                    <div class="row" >
                      <label class="pull-right">@lang('dashboard.last_opened_tx')</label>
                    </div>
                    <div class="row pull-right" style="margin-top:4px">
                      @foreach($last_opened as $doc)
                      <div class="col-xs-2 col-sm-1 prev_viewed_docs"  onclick="window.location='{{ url('document/'.$doc->view_doc_id) }}'">
                        <img src="{{ url('/files/image') .'/'. $doc->thumbnail }}" class="img-responsive">
                      </div>
                      @endforeach
                    </div>
                    @endif
                  </div>
                  <!-- Last opened mobile  -->
                  <div class="col-xs-7 col-sm-9 visible-xs visible-sm">
                    @if(count($last_opened)>=1)
                    <div class="row" >
                      <label class="pull-right">zuletzt geöffnet</label>
                    </div>
                    <div class="row pull-right" style="margin-top:24px">
                      @foreach($last_opened->slice(0, 3) as $docs)
                      <div class="col-xs-2 col-sm-1 prev_viewed_docs_m" onclick="window.location='{{ url('document/'.$docs->view_doc_id) }}'">
                        <img src="{{ url('/files/image') .'/'. $docs->thumbnail }}" class="img-responsive">
                      </div>
                      @endforeach
                    </div>
                    @endif
                  </div>

                </div>
              </div>
            </div>


            <!-- EMPTY -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card dashboard_card">
                <div class="body">
                   <ul class="list-group" >
                      <a href="/merge_pdf" style="text-decoration: none !important">
                        <button type="button" class="list-group-item cstm_list_g waves-effect waves-blue"><label>Merge documents</label></button>
                      </a>
                      <a href="/address_book" style="text-decoration: none !important">
                        <button type="button" class="list-group-item cstm_list_g waves-effect waves-blue"><label>Manage Address Books</label></button>
                      </a>
                    </ul>
                </div>
              </div>
            </div>


            <!-- EMPTY -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card dashboard_card">
                <div class="body">

                </div>
              </div>
            </div>

    </div> <!-- grid row-->

    <!-- Documents table search result -->
    <div class="col-md-12" ng-click="clear_autocomplete()">
      <table class="table table-hover ng-hide table-striped" ng-show="documents_table">
        <thead style="background-color:#ebedf8; color:#000; font-size:13px; ">
          <th>#</th>
          <th>Recipient</th>
          <th>Sender</th>
          <th>Category</th>
          <th>OCRED</th>
          <th>Date</th>
          <th>Actions</th>
        </thead>
        <tbody style="font-size:13px;">
          <tr ng-repeat="data in documents track by $index">
            <td><#$index+1#></td>
            <td><# data.receiver | default #></td>
            <td><# data.sender   | default #></td>
            <td><# data.category | default #></td>
            <td ng-bind-html="data.process_status  | ocr_status "></td>
            <td><# data.date     | default  #></td>    
            <td style="width:300px">
              <!-- Edit document -->
              <a ng-href="/document/<#data.doc_id#>" style="text-decoration: none">
                <button type="button" class="btn btn-default waves-effect cstm_icon_btn" data-toggle="tooltip" title="" data-original-title="Edit document" tooltip-top>
                  <i class="material-icons cstm_icon_btn_ico">edit</i>
                </button>
              </a>
              <!-- View Document in PDF viewer -->
              <a ng-href="/files/ocr/<#data.doc_ocr#>" style="text-decoration: none" target="_blank">
                <button type="button" class="btn btn-default waves-effect cstm_icon_btn" data-toggle="tooltip" title="" data-original-title="View document" tooltip-top>
                  <i class="material-icons cstm_icon_btn_ico">remove_red_eye</i>
                </button>
              </a>
                  <span ng-if="data.approved==0">
                    <!-- Download original document. -->
                    <a ng-href="/files/org/<#data.doc_org#>" style="text-decoration: none" download="<#data.download_format#>">
                      <button type="button" class="btn btn-default waves-effect cstm_icon_btn" data-toggle="tooltip" title="" data-original-title="Download original file" tooltip-top>
                        <i class="material-icons cstm_icon_btn_ico">file_download</i>
                      </button>
                    </a>
                    <!-- Approved document. delete original -->
                    <button ng-click="approveDocument(data.doc_id,data.doc_org)" type="button" class="btn btn-default waves-effect cstm_icon_btn doc-upd-btn" data-toggle="tooltip" title="" data-original-title="Approve document" tooltip-top id="deleteDocBtn<#doc.doc_id#>">
                      <i class="material-icons cstm_icon_btn_ico">check</i>
                    </button>
                  </span>

              <!-- customize document -->
              <a ng-href="/customize_pdf/<#data.doc_id#>" style="text-decoration: none">  
                <button type="button" class="btn btn-default waves-effect cstm_icon_btn doc-upd-btn" data-toggle="tooltip" title="" data-original-title="Customize document" tooltip-top>
                  <i class="material-icons cstm_icon_btn_ico">build</i>
                </button>
              </a>

              <button ng-click="deleteDocument(data.doc_id)" type="button" class="btn btn-default waves-effect cstm_icon_btn doc-upd-btn" data-toggle="tooltip" title="" data-original-title="Delete document" tooltip-top id="deleteDocBtn<#doc.doc_id#>">
                <i class="material-icons cstm_icon_btn_ico">delete_forever</i>
              </button>
              
            </td>
          </tr>
        </tbody>
      </table>
      <center>
        <div class="preloader ng-hide center-block" ng-show="dashboard_preloader" style="margin-top:100px">
          <div class="spinner-layer pl-blue">
            <div class="circle-clipper left">
              <div class="circle"></div>
            </div>
            <div class="circle-clipper right">
              <div class="circle"></div>
            </div>
          </div>
        </div>

        <div class="center-block ng-hide" ng-show="not_found" style="margin-top:100px">
          <h4 style="color:red">No document found.</h4>
        </div>
      </center>
    </div>


</div> <!-- / main row -->
@endsection


@section('scripts')
<script src="{{ asset('static/js/dashboard.js') }}"></script>
<script type="text/javascript">

//inject this app to rootApp
var app = angular.module('app', ['ngSanitize']);

app.directive('tooltipTop', function() {
      return function(scope, element, attrs) {
      element.tooltip({
        trigger:"hover",
        placement: "top",
      });

    };
});

app.filter('default', function(){
   return function(data){
       if(data==null){
           data = "N/D";
           return data;
       }
       return data;
   }
});

app.filter('default_id', function(){
   return function(data){
       if(data==null){
           data = "no_id";
           return data;
       }
       return data;
   }
});

app.filter('ocr_status', function(){
   return function(data){
       if(data=="ocred_final"){
           data = "<b class='ocr_success'>"+"YES"+"</b>";
           return data;
       }else{
           data = "<b class='ocr_failed'>"+"NO"+"</b>";
           return data;
       }
   }
});


app.controller('dashboard_controller', function($scope, $http, $timeout, $q) {

    // cancel previous http request
    // eg running autocomplete. if user press enter search. cancel all previous running http request.
    $scope.canceler = $q.defer();
    $scope.search_canceler = $q.defer();
    //search autocomplete preloader
    $scope.searchAutoCompLoader = false;

    //show/hide numbers of documents to edit.
    $scope.num_to_edit =         false;
    //show/hide numbers of archive documents.
    $scope.num_to_archive =      false;
    //show/hide preloader for documents numeric values
    $scope.num_data_preloader =   true;


    //show/hide dashboard cards.
    $scope.dashboard_grid =      true;
    //show/hide dashboard preloader.
    $scope.dashboard_preloader = false;
    //show/hide not found div.
    $scope.not_found =           false;
    //show hide documents table result.
    $scope.documents_table =     false;


// clear autocomplete on search bar
$scope.clear_autocomplete = function(){
    $scope.ac_tags     = null;
    $scope.ac_folders  = null;
    $scope.ac_fulltext = null;
    $scope.no_result_found = false;
}

// show preloader when user select keyword to search
$scope.show_preloader = function(){
    $scope.dashboard_grid =      false;
    $scope.not_found =           false;
    $scope.dashboard_preloader = true;
    $scope.documents_table =     false;
}
// hide preloader when user got result.
$scope.hide_preloader = function(){
    $scope.dashboard_grid =      true;
    $scope.dashboard_preloader = false;
    $scope.not_found =           false;
    $scope.documents_table =     false;
}
// show not found div when search has no result.
$scope.doc_not_found = function(){
    $scope.dashboard_grid =      false;
    $scope.dashboard_preloader = false;
    $scope.not_found =           true;
    $scope.documents_table =     false;
}

// get request to get numbers of documents to edit and archived documents.
$scope.getNumToEditArchive = function(){
  $http.get('/get_docs_edit_archive').success(function(data){
       //set number of documents to be edit
       $scope.num_edit = data.num_to_edit;
       $scope.num_data_preloader = false;
       $scope.num_to_edit = true;
       //set number of archive documents.
       $scope.archive =  data.num_archived;
       $scope.num_to_archive = true;
       //set num of queue docs.
       $scope.queueDocs = data.num_queue;
       //set num of failed docs.
       $scope.failedDocs = data.num_failed_docs;
       //set oldest doc id, when user click documents to edit, user will be redirected to edit documents
       //with the oldest doc needed to be edit.
       $scope.oldest_doc = data.oldest_doc;
       //
       $scope.doc_failed = data.oldest_doc_failed;
       //set week days for barchart
       $scope.weekDays = data.week;
       //set docs count each week days.
       $scope.docCounts  =  data.bar_datas;

       $scope.makeBarChart();
  });
}
// run getNumToEditArchive on page load.
$scope.getNumToEditArchive();

// run getNumToEditArchive every specified interval 20000 = 2 seconds.
angular.element(document).ready(function () {
    //check for document status
    setInterval(function() {
         // method to be executed;
         $scope.getNumToEditArchive();
    },20000);
});


// on keypress check key
$scope.searchKeyPress = function(keyEvent) {

  //if key == 13 == ENTER  search document.
  if (keyEvent.which === 13){
      //delay function for 1 second
      $timeout( function()
      {
        // method to be executed;
            $scope.searchDocuments($scope.doc_keyword,'no_filter')
      }, 1000); //end timeout.
  }
  // key 8 = backspace. clear autocomplete
  if (keyEvent.which === 8){
    $scope.clear_autocomplete();
    if($scope.doc_keyword==""){
        $scope.hide_preloader();
    }

  }
};


// show autocomplete
$scope.onChangeInput = function(){

    //show autocomplete preloader.
    if($scope.doc_keyword.length==0){
      $scope.searchAutoCompLoader = false;
    }else{
      $scope.searchAutoCompLoader = true;
    }

    //cancel previous autocomplete post request.
    $scope.canceler.resolve();
    //reinit $q.defer make new autocomplete post request
    $scope.canceler = $q.defer();
    // check if search input has value
    if($scope.doc_keyword!="" && $scope.doc_keyword!=null && $scope.doc_keyword!=undefined){
        //clear dropdown autocomplete
        $scope.clear_autocomplete();
        //store keyword to data to be passed in post request
        data = {
            doc_keyword: $scope.doc_keyword
        }
        //make post request to get if keyword is found in documents tags,folder or page text.
        $http({method:'POST',url:'/common_search/autocomplete', data, timeout: $scope.canceler.promise}).success(function(data){
            //if notthing is found, show not found dropdown result.
            if(data.tags=="not_found" && data.folders=="not_found" && data.fulltext=="not_found"){
              //not found
              $scope.no_result_found = true;
            }else{
              //store result to be displayed in autocomplete.
              $scope.ac_tags     = data.tags;
              $scope.ac_folders  = data.folders;
              $scope.ac_fulltext = data.fulltext;
            }
            $scope.searchAutoCompLoader = false;
        });
    }
}



// search documents function
$scope.searchDocuments = function(keyword,filter){
    //hide search autocomplete preloader
    $scope.searchAutoCompLoader = false;
    //clear autocomplete
    $scope.clear_autocomplete();
    //cancel previous autocomplete post request.
    $scope.canceler.resolve();
    //reinit $q.defer make new autocomplete post request
    //$scope.canceler = $q.defer();
    $scope.doc_keyword = keyword;  
    //-------------------------------------------------
    //cancel previous selectSearch post request
    $scope.search_canceler.resolve();
    //reinit $q.defer to make new post request.
    $scope.search_canceler = $q.defer();
    $scope.show_preloader();

    data = {
        doc_keyword: keyword,
        doc_filter: filter
    }
 
    $http({method:'POST',url:'/common_search/search', data, timeout: $scope.search_canceler.promise}).success(function(data){        
         if(data=="error"){
             $scope.doc_not_found();
         }else{
             //pass result to scope documents to be rendered in table
             $scope.documents = data;
             //make documents table visible
             $scope.documents_table = true;
             //hide preloader
             $scope.dashboard_preloader = false;
             console.log(data);
         }

      }); //end http


}//end searchDocuments.


// delete document
$scope.deleteDocument = function(doc_id){

    var doc_ids = [doc_id];
    swal({
      title: "Delete document?",
      text: "You will not be able to recover this document after you delete.",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes, delete it!",
      cancelButtonText: "No, cancel please!",
      closeOnConfirm: true,
      closeOnCancel: false
    }, function (isConfirm) {
      if(isConfirm) {
         //ajax send post delete with id.
         $('.doc-upd-btn').attr("disabled", "disabled");


         $.ajax({
            url: '/document/delete',
            data: {
                doc_id: doc_ids
            },
            type: 'POST',
            success: function(data) {
                swal("Deleted!", "Document has been deleted.", "success");
                $scope.searchDocuments();
                $('.doc-upd-btn').removeAttr('disabled');
            }
        }); //end ajax
      }else{
        swal("Cancelled", "Your document is safe :)", "error");
      }
    });
}


//approve document will delete the original doc in the server.
$scope.approveDocument = function(doc_id,doc_org){

    swal({
      title: "Approve this document?",
      text: "Approving document will delete the original file from our server",
      type: "success",
      showCancelButton: true,
      confirmButtonColor: "#b1d5ff",
      confirmButtonText: "Yes, Approve it!",
      cancelButtonText: "No, cancel please!",
      closeOnConfirm: true,
      closeOnCancel: false
    },function (isConfirm) {
        if(isConfirm){
           //ajax send post delete with id.
           $('.doc-upd-btn').attr("disabled", "disabled");
           $.ajax({
              url: '/document/approve',
              data: {
                  doc_id: doc_id,
                  doc_org: doc_org
              },
              type: 'POST',
              success: function(data) {
                  swal("Success!", "Document has been approved.", "success");
                  $scope.searchDocuments();
                  $('.doc-upd-btn').removeAttr('disabled');
              }
          }); //end ajax
        }else{
          swal("Cancelled", "Your document is safe :)", "error");
        }
    });
}




// BAR CHART ==================================================
// CUSTOM BARCHART


function getData() {
  var dataSize = 7;
  var evenBackgroundColor = 'rgba(0, 119, 255, 1)';
  var oddBackgroundColor = 'rgba(177,213,255, 1)';

  var docs  = $scope.docCounts;
  var weeks = $scope.weekDays;
  var labels = [];

  var docDatas = {
    label: 'Documents:',
    data: [],
    backgroundColor: [],
    borderColor: [],
    borderWidth: 1,
    hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
    hoverBorderColor: 'rgba(200, 200, 200, 1)',
  };

  for (var i = 0; i < dataSize; i++) {
    
    docDatas.data.push(docs[i]);
    labels.push(weeks[i]);

    if (i % 2 === 0) {
      docDatas.backgroundColor.push(evenBackgroundColor);
    } else {
      docDatas.backgroundColor.push(oddBackgroundColor);
    }
  }

  return {
    labels: labels,
    datasets: [docDatas],
  };
};

$scope.makeBarChart = function(){

  var chartData = getData();

  var myBar = new Chart(document.getElementById("myChart").getContext("2d"), {
    type: 'bar',
    data: chartData,
    options: {
      maintainAspectRatio: false,
      title:{
        display: false
      },
      legend: {
        display: false
      },

      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            autoSkip: false,
            display:false
          },
           gridLines: {
          display: false,
          color: "white",
            zeroLineColor: "white"
        },
        }],
        xAxes: [{
          ticks: {
            beginAtZero: true,
            autoSkip: false,
            display:false
          },
          gridLines: {
          display: false,
          color: "white",
            zeroLineColor: "white"
        },
        categoryPercentage: 1,

        }]
      }
    }
  });

};




//end controller
});


// modefiy bars add border radius
Chart.elements.Rectangle.prototype.draw = function() {
    var ctx = this._chart.ctx;
    var vm = this._view;
    var left, right, top, bottom, signX, signY, borderSkipped, radius;
    var borderWidth = vm.borderWidth;
    // Set Radius Here
    // If radius is large enough to cause drawing errors a max radius is imposed
    var cornerRadius = 5;

    if (!vm.horizontal) {
        // bar
        left = vm.x - vm.width / 2;
        right = vm.x + vm.width / 2;
        top = vm.y;
        bottom = vm.base;
        signX = 1;
        signY = bottom > top? 1: -1;
        borderSkipped = vm.borderSkipped || 'bottom';
    } else {
        // horizontal bar
        left = vm.base;
        right = vm.x;
        top = vm.y - vm.height / 2;
        bottom = vm.y + vm.height / 2;
        signX = right > left? 1: -1;
        signY = 1;
        borderSkipped = vm.borderSkipped || 'left';
    }

    // Canvas doesn't allow us to stroke inside the width so we can
    // adjust the sizes to fit if we're setting a stroke on the line
    if (borderWidth) {
        // borderWidth shold be less than bar width and bar height.
        var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
        borderWidth = borderWidth > barSize? barSize: borderWidth;
        var halfStroke = borderWidth / 2;
        // Adjust borderWidth when bar top position is near vm.base(zero).
        var borderLeft = left + (borderSkipped !== 'left'? halfStroke * signX: 0);
        var borderRight = right + (borderSkipped !== 'right'? -halfStroke * signX: 0);
        var borderTop = top + (borderSkipped !== 'top'? halfStroke * signY: 0);
        var borderBottom = bottom + (borderSkipped !== 'bottom'? -halfStroke * signY: 0);
        // not become a vertical line?
        if (borderLeft !== borderRight) {
            top = borderTop;
            bottom = borderBottom;
        }
        // not become a horizontal line?
        if (borderTop !== borderBottom) {
            left = borderLeft;
            right = borderRight;
        }
    }

    ctx.beginPath();
    ctx.fillStyle = vm.backgroundColor;
    // ctx.strokeStyle = vm.borderColor;
    ctx.lineWidth = borderWidth;

    // Corner points, from bottom-left to bottom-right clockwise
    // | 1 2 |
    // | 0 3 |
    var corners = [
        [left, bottom],
        [left, top],
        [right, top],
        [right, bottom]
    ];

    // Find first (starting) corner with fallback to 'bottom'
    var borders = ['bottom', 'left', 'top', 'right'];
    var startCorner = borders.indexOf(borderSkipped, 0);
    if (startCorner === -1) {
        startCorner = 0;
    }

    function cornerAt(index) {
        return corners[(startCorner + index) % 4];
    }

    // Draw rectangle from 'startCorner'
    var corner = cornerAt(0);
    ctx.moveTo(corner[0], corner[1]);

    for (var i = 1; i < 4; i++) {
        corner = cornerAt(i);
        nextCornerId = i+1;
        if(nextCornerId == 4){
            nextCornerId = 0
        }

        nextCorner = cornerAt(nextCornerId);

        width = corners[2][0] - corners[1][0];
        height = corners[0][1] - corners[1][1];
        x = corners[1][0];
        y = corners[1][1];

        var radius = cornerRadius;

        // Fix radius being too large
        if(radius > height/2){
            radius = height/2;
        }if(radius > width/2){
            radius = width/2;
        }

        ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
        ctx.lineTo(x + width, y + height - radius);
        ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
        ctx.lineTo(x + radius, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
        ctx.lineTo(x, y + radius);
        ctx.quadraticCurveTo(x, y, x + radius, y);

    }

    ctx.fill();
    if (borderWidth) {
        ctx.stroke();
    }
};





</script>
@endsection
