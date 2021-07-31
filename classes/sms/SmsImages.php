<?php
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/libraries/aws.phar');

class SmsImages
{

    private static $s3 = null;
    const bucket = 'data.simonline';

    private static $credentials = array (
            'version' => S3_VERSION,
            'region' => "ap-south-1",
            'credentials' => [
                    'key'    => S3_KEY,
                    'secret' => S3_SECRET,
            ],
    );
    public function __construct()
    {
        if (self::$s3 == null) {
            self::$s3 = Aws\S3\S3Client::factory ( self::$credentials  );
        }
    }

    public function uploadFileToSimOnline($fileKey, $filename){
        error_log("Saving ".$fileKey. " to =". $filename);
        $result = self::$s3->upload(
                self::bucket,
                $fileKey,
                fopen($filename, 'rb'),
                'public-read',
                array('params' => array('ContentType' => 'image/png'))
                );
        error_log("Result=".print_r($result, true));
        return $result;
    }

    public function uploadObjectToSimOnline($fileKey, $imageData){
        error_log("Saving object as =". $fileKey);
        $result = self::$s3->upload(
                self::bucket,
                $fileKey,
                $imageData,
                'public-read',
                array('params' => array('ContentType' => 'image/png'))
                );
        error_log("Result=".print_r($result, true));
        return $result;
    }
    public function imageCount($bot_id){
        $totalCount = 0;
        $iterator = self::$s3->getIterator('ListObjects', array(
                'Bucket' => self::bucket, 'Delimiter' => '/', 'Prefix' => $bot_id.'/img/'
        ));
        error_log("Getting folder size");
        foreach ($iterator as $object) {
            $totalCount += 1;
        }
        error_log("Got folder size ".$totalCount);
        return $totalCount;
    }

    public function listImages($bot_id) // format 2016/06/02
    {
        $images=array();
        $iterator = self::$s3->getIterator('ListObjects', array(
                'Bucket' => self::bucket, 'Delimiter' => '/', 'Prefix' => $bot_id.'/img/'
        ));
        foreach ($iterator as $object) {
            $images[] = "https://s3.ap-south-1.amazonaws.com/data.simonline/".$object['Key'];
        }
        return $images;
    }

    public function copyImages($from_bot_id, $to_bot_id)
    {
        $iterator = self::$s3->getIterator('ListObjects', array(
                'Bucket' => self::bucket, 'Delimiter' => '/', 'Prefix' => $from_bot_id.'/img/'
        ));
        foreach ($iterator as $object) {
            $fn = basename($object['Key']);
            error_log($fn);
            $result = self::$s3->copyObject([
                    'Bucket'     => self::bucket,
                    'Key'        => $to_bot_id.'/img/'.$fn,
                    'CopySource' => self::bucket.'/'.$from_bot_id.'/img/'.$fn,
                    'ACL'    => 'public-read'
            ]);
            //error_log(print_r($result, true));
        }
    }

    public function logo($bot_id){
        $iterator = self::$s3->getIterator('ListObjects', array(
                'Bucket' => self::bucket, 'Delimiter' => '/', 'Prefix' => $bot_id.'/img/'
        ));
        foreach ($iterator as $object) {
            if (strpos($object['Key'], "logo") > 0){
                return "https://s3.ap-south-1.amazonaws.com/data.simonline/".$object['Key'];
            }
        }
        return null;
    }

    public function deleteImageFolder($bot_id){
        $iterator = self::$s3->getIterator('ListObjects', array(
                'Bucket' => self::bucket, 'Delimiter' => '/', 'Prefix' => $bot_id.'/img/'
        ));
        foreach ($iterator as $object) {
            self::$s3->deleteMatchingObjects(self::bucket, $object['Key']);
        }
    }

    public function deleteImage($prefix){
        $result = self::$s3->deleteMatchingObjects(self::bucket, $prefix);
        //error_log(print_r($result, true));
    }


}
?>