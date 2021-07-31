<?php
require_once (__ROOT__ . '/libraries/aws.phar');
require_once(__ROOT__.'/config/config.php');



class AwsStore {
    private static $s3 = null;
    private static $s3_sqlite = null;
    private static $queue_dict = array ();
    private static $credentials =  array (
            'version' => S3_VERSION,
            'region' => "ap-south-1",
            'credentials' => [
                    'key'    => S3_KEY,
                    'secret' => S3_SECRET,
            ],
    );
    const bucket = 'onedodropbox';
    public function __construct() {
        if (self::$s3 == null) {
            self::$s3 = Aws\S3\S3Client::factory ( self::$credentials );
        }
        if (self::$s3_sqlite == null) {
            self::$s3_sqlite = new SQLite3( __ROOT__.'/data/95/db-95.s3_folders.db', SQLITE3_OPEN_READWRITE);
            self::$s3_sqlite->enableExceptions(true);
        }
    }
    
    public function addFolder($number, $folder){
        $sql = sprintf("select folders from s3_folders where number='%s';",$number);
        $result = self::$s3_sqlite->query($sql);
        $data = array();
        $results = $result->fetchArray(2)[0];
        $vals = explode(",", $results);
        if (count($vals) > 20) {
            return "There are more than 20 folders, cannot add more.";
        }
        foreach($vals as $val){
            if ($val != $folder){
                $data[] = $val;
            }
        }
        $data[] = $folder;
        $sql = sprintf("insert or replace into s3_folders values ('%s', '%s', '%s');", $number, SQLite3::escapeString((string)implode(",", $data)), time());
        $result = self::$s3_sqlite->query($sql);
        return null;
    }
    
    public function getFolders($number){
        $sql = sprintf("select folders from s3_folders where number='%s';",$number);
        $result = self::$s3_sqlite->query($sql);
        $results = $result->fetchArray(2)[0];
        return $results;
    }
    
    public function deleteFolder($number, $folder){
        $sql = sprintf("select folders from s3_folders where number='%s';",$number);
        $result = self::$s3_sqlite->query($sql);
        $data = array();
        $results = $result->fetchArray(2)[0];
        $vals = explode(",", $results);
        foreach($vals as $val){
            if ($val != $folder){
                $data[] = $val;
            }
        }
        $sql = sprintf("insert or replace into s3_folders values ('%s', '%s', '%s');", $number, SQLite3::escapeString((string)implode(",", $data)), time());
        $result = self::$s3_sqlite->query($sql);
        return null;
    }
    
    public function uploadUserFileToSimOnline($username,  $FILES, $folder) {
        $error = false;
        if ($FILES["fileToUpload"]["error"] != 0){
            error_log(' File upload failed for '.$FILES["fileToUpload"]["name"] . ' with error ' . $FILES["fileToUpload"]["error"] . ' size is ' . $FILES["fileToUpload"]["size"]);
            print_r( $result);
            $msg=" Unknown error ";
            return "File upload failed, Unknown Error.";
        }
        
        if ($FILES["fileToUpload"]["size"] > 10000000) {
            error_log('File is too large for '.$FILES["fileToUpload"]["name"] . ' with error ' . $FILES["fileToUpload"]["size"]);
            $msg=$msg." File size of less than 500k is allowed ";
            $error = true;
            return "File upload failed, Size limite exceeded !";
        }
        
        if ($this->fileCount($username) > 110){
            return "File upload failed, You have more than 100 files, delete some to free up your quota !";
        }
        
        error_log(print_r($FILES, true));
        
        $filePath = $FILES["fileToUpload"]["tmp_name"];
        $filename = $FILES["fileToUpload"]["name"];
        $fileKey = "user_store/" . $username . "/" . $filename;
        if ($folder != "/"){
            $fileKey = "user_store/" . $username . "/" . $folder ."/". $filename;
        }
        error_log ( "FileName=".$filename);
        $contentType = mime_content_type($filePath) ;
        error_log ( "Saving " . $fileKey . " to =" . $filePath ." contentType=".$contentType);
        $result = self::$s3->upload ( 
                self::bucket, $fileKey, fopen ( $filePath, 'rb' ), 'private', 
                array ('params' => array ('ContentType' => $contentType) ) );
        error_log ( "Result=" . print_r ( $result, true ) );
        return $fileKey;
    }
    
    public function getFileListing($number) {
        $files = array ();
        $iterator = self::$s3->getIterator ( 'ListObjects', array ('Bucket' => self::bucket,'Delimiter' => '/','Prefix' =>  "user_store/" . $number . "/"
        ) );
        foreach ( $iterator as $object ) {
            $files [] = $object ['Key'];
        }
        return $files;
    }
    
    public function getFileListingForFolder($number, $folder) {
        $prefix="user_store/" . $number . "/";
        if ($folder != ""){
            $prefix = "user_store/" . $number . "/".$folder."/";
        }
        $files = array ();
        $iterator = self::$s3->getIterator ( 'ListObjects', array ('Bucket' => self::bucket,'Delimiter' => '/','Prefix' =>  $prefix
        ) );
        foreach ( $iterator as $object ) {
            $files [] = $object ['Key'];
        }
        return $files;
    }
    public function getSignedFileUrl($fileKey) {
        $cmd = self::$s3->getCommand ( 'GetObject',
                [
                        'Bucket' => self::bucket,
                        'Key' => ( string ) $fileKey
                ] );
        
        $request = self::$s3->createPresignedRequest ( $cmd, '+240 minutes' );
        
        //error_log(print_r($request, true));
        // Get the actual presigned-url
        $presignedUrl = ( string ) $request->getUri ();
        return $presignedUrl;
    }
    
    public function fileCount($number){
        $fileKey = "user_store/" . $number . "/";
        $totalCount = 0;
        $iterator = self::$s3->getIterator('ListObjects', array(
                'Bucket' => self::bucket, 'Delimiter' => '/', 'Prefix' => $fileKey
        ));
        //error_log("Getting folder size");
        foreach ($iterator as $object) {
            $totalCount += 1;
        }
        error_log("Got folder size ".$totalCount);
        return $totalCount;
    }
    
    public function deleteFile($prefix){
        $result = self::$s3->deleteMatchingObjects(self::bucket, $prefix);
        error_log($prefix. " Deleting S3=".print_r($result, true));
    }
    
    
    public function deleteNumber($number){
        $iterator = self::$s3->getIterator('ListObjects', array(
                'Bucket' => self::bucket, 'Delimiter' => '/', 'Prefix' => 'user_store/'.$number."/"
        ));
        foreach ($iterator as $object) {
            self::$s3->deleteMatchingObjects(self::bucket, $object['Key']);
        }
    }
    
}
?>