<?php

/**
 * Accential API class.
 */
class AccentialApiComponent extends Component {

    public $components = array('CurlRequest');
    private $paramsValidationStatus;
    private $ftpUploadConfig;
    private $ftpAccentialUploadConfig;

    public function __construct($request = null, $response = null) {
        if (!$this->paramsValidationStatus) {
            $this->paramsValidationStatus = Configure::read('paramsValidationStatus');
        }
        if (!$this->ftpUploadConfig){
            $this->ftpUploadConfig = Configure::read('ftpUploadConfig');
        }
        if (!$this->ftpAccentialUploadConfig){
            $this->ftpAccentialUploadConfig = Configure::read('ftpAccentialUploadConfig');
        }
        parent::__construct($request, $response);
    }

    /**
     * Just to test this class.
     */
    public function accentialApiTestMethod() {
        return "Retorno: accentialApiTestMethod - CLASS: AccentialApiComponent";
    }

    /**
     * Responsible for requesting data to the API.
     */
    public function urlRequestToGetData($api, $type, $params) {
        $apiUrl = API_ENVIRONMENT_URL . '/';
        $apiUrl .= $api . '/get';
        $apiUrl .= '/' . $type;
        if (!empty($params) && is_array($params)) {
            $paramsJson = json_encode($params);
            $params = array(
                'params' => $paramsJson
            );
        } else {
            return $this->paramsValidationStatus ['invalid_param'];
        }
        $apiUrl .= '/' . $this->generateSecureToken();
        $data = $this->CurlRequest->curlRequest($apiUrl, $params);
        return $this->decodeData($data);
    }

    /**
     * Responsible for registering / editing data of a given API	
     */
    public function urlRequestToSaveData($api, $params) {
        $apiUrl = API_ENVIRONMENT_URL . '/';
        $apiUrl .= $api . '/save';
        $apiUrl .= '/all';
        if (!empty($params) && is_array($params)) {
            $paramsJson = json_encode($params);
            $params = array(
                'params' => $paramsJson
            );
            $apiUrl .= '/' . $this->generateSecureToken();
            $data = $this->CurlRequest->curlRequest($apiUrl, $params);
            return $this->decodeData($data);
        } else {
            return $this->paramsValidationStatus ['invalid_param'];
        }
    }

    //TODO: rever estes metodos de upload image, como estão estruturados nao tem muito sentido
    /**
     * Responsible to send file to ftp server
     */
    public function uploadFileComp($folder = null, $fileData, $recursive = false) {
        $data = '';
        if ($recursive) {
            foreach ($fileData as $file) {
                $data [] = $this->uploadFile($folder, $file);
            }
            return $data;
            exit();
        } else {
            $ftpConnectionId = ftp_connect('192.168.1.200');
            $ftpLogin = ftp_login($ftpConnectionId, 'ftpuser', 'ACCftp1000');
        }
        $folder = (!$folder ? '' : $folder . '/');
        $explodeFileData = explode('.', $fileData ['name']);
        $fileExt = end($explodeFileData);
        $renamedRemoteFile = uniqid() . '.' . $fileExt;
        $pathToUpload = LOCAL_PATH_TO_UPLOAD . $renamedRemoteFile;
        $localFile = $fileData ['tmp_name'];
        if ($ftpLogin) {
            ftp_pasv($ftpConnectionId, true);
            if (ftp_put($ftpConnectionId, $pathToUpload, $localFile, FTP_BINARY)) {
                $data = FTP_ENVIRONMENT_URL . $renamedRemoteFile;
            } else {
                $data = 'UPLOAD_ERROR';
            }
        } else {
            $data = 'NO_CONNECTION';
        }
        ftp_close($ftpConnectionId);
        return $data;
    }

    /**
     * Responsible to send file to ftp server
     */
    private function uploadFile($folder = null, $fileData, $recursive = false) {
        $data = '';
        if ($recursive) {
            foreach ($fileData as $file) {
                $data [] = $this->uploadFile($folder, $file);
            }
            return $data;
            exit();
        } else {
            $ftpConnectionId = ftp_connect("192.168.1.200");
            $ftpLogin = ftp_login($ftpConnectionId, "ftpuser", "ACCftp1000");
        }
        $folder = (!$folder ? '' : $folder . '/');
        $fileExt = end(explode('.', $fileData ['name']));
        $renamedRemoteFile = uniqid() . '.' . $fileExt;
        $pathToUpload = $this->ftpUploadConfig ['uploadPath'] . $this->ftpUploadConfig ['uploadFolder'] . $folder . $renamedRemoteFile;
        $localFile = $fileData ['tmp_name'];
        if ($ftpLogin) {
            ftp_pasv($ftpConnectionId, true);
            if (ftp_put($ftpConnectionId, $pathToUpload, $localFile, FTP_BINARY)) {
                $data = $this->ftpUploadConfig ['url'] . '/' . $this->ftpUploadConfig ['uploadFolder'] . $folder . $renamedRemoteFile;
            } else {
                $data = 'UPLOAD_ERROR';
            }
        } else {
            $data = 'NO_CONNECTION';
        }
        ftp_close($ftpConnectionId);
        return $data;
    }

    public function uploadFileOffer($folder = null, $fileData, $recursive = false) {
        $data = '';
        if ($recursive) {
            foreach ($fileData as $file) {
                $data [] = $this->uploadFile($folder, $file);
            }
            return $data;
            exit();
        } else {
            $ftpConnectionId = ftp_connect($this->ftpAccentialUploadConfig ['host']);
            $ftpLogin = ftp_login($ftpConnectionId, $this->ftpAccentialUploadConfig ['user'], $this->ftpAccentialUploadConfig ['password']);
        }
        $folder = (!$folder ? '' : $folder . '/');
        $explodeFileData = explode('.', $fileData ['name']);
        $fileExt = end($explodeFileData);
        $renamedRemoteFile = uniqid() . '.' . $fileExt;
        $pathToUpload = LOCAL_PATH_TO_UPLOAD_IMAGE . $renamedRemoteFile;
        $localFile = $fileData ['tmp_name'];
        if ($ftpLogin) {
            ftp_pasv($ftpConnectionId, true);
            if (ftp_put($ftpConnectionId, $pathToUpload, $localFile, FTP_BINARY)) {
                $data = FTP_ENVIRONMENT_URL_IMAGES . $renamedRemoteFile;
            } else {
                $data = 'UPLOAD_ERROR';
            }
        } else {
            $data = 'NO_CONNECTION';
        }
        ftp_close($ftpConnectionId);
        return $data;
    }
	
	public function uploadAnyPhoto($folder = null, $fileData, $recursive = false) {
			
		// set up basic connection 
		$conn_id = ftp_connect($this->ftpAccentialUploadConfig['host']); 

		// login with username and password 
		$login_result = ftp_login($conn_id, $this->ftpAccentialUploadConfig['user'], $this->ftpAccentialUploadConfig['password']); 

		$myFile = $fileData; 
		$type = explode("/",$fileData['type']);
   
		$destination_path = "jezzyuploads/company-119/offers/"; 
		$destination_file = $destination_path.$fileData['name'];
	
		$file = $myFile['tmp_name'];
	
	 $upload = ftp_put($conn_id, $destination_file, $file, FTP_BINARY);// upload the file
    if (!$upload) {// check upload status
        return "UPLOAD_ERROR";
    } else {
        return "http://192.168.1.200/".$destination_file;
    }
}

public function uploadAnyPhotos($folder = null, $fileData, $companyId, $recursive = false) {
			
		// set up basic connection 
		$conn_id = ftp_connect($this->ftpAccentialUploadConfig['host']); 

		// login with username and password 
		$login_result = ftp_login($conn_id, $this->ftpAccentialUploadConfig['user'], $this->ftpAccentialUploadConfig['password']); 

		$myFile = $fileData; 
		$type = explode("/",$fileData['type']);
   
		$destination_path = "jezzy/uploads/company-".$companyId."/offers/"; 
		$destination_file = $destination_path.$fileData['name'];
	
		$file = $myFile['tmp_name'];
	
	 $upload = ftp_put($conn_id, $destination_file, $file, FTP_BINARY);// upload the file
    if (!$upload) {// check upload status
        return "UPLOAD_ERROR";
    } else {
        return "http://192.168.1.200/".$destination_file;
    }
}

public function uploadAnyPhotoCompanyLogo($folder = null, $fileData, $recursive = false) {
			
		// set up basic connection 
		$conn_id = ftp_connect($this->ftpAccentialUploadConfig['host']); 

		// login with username and password 
		$login_result = ftp_login($conn_id, $this->ftpAccentialUploadConfig['user'], $this->ftpAccentialUploadConfig['password']); 

		$myFile = $fileData; 
		$type = explode("/",$fileData['type']);
   
		$destination_path = "jezzy/uploads/company-119/config/"; 
		$destination_file = $destination_path.$fileData['name'];
	
		$file = $myFile['tmp_name'];
	
	 $upload = ftp_put($conn_id, $destination_file, $file, FTP_BINARY);// upload the file
    if (!$upload) {// check upload status
        return "UPLOAD_ERROR";
    } else {
        return "http://192.168.1.200/".$destination_file;
    }
}

public function uploadAnyPhotoCompany($folder = null, $fileData, $companyId, $recursive = false) {
			
		// set up basic connection 
		$conn_id = ftp_connect($this->ftpAccentialUploadConfig['host']); 

		// login with username and password 
		$login_result = ftp_login($conn_id, $this->ftpAccentialUploadConfig['user'], $this->ftpAccentialUploadConfig['password']); 

		$myFile = $fileData; 
		$type = explode("/",$fileData['type']);
   
		//$destination_path = "jezzyuploads/company-".$companyId."/config/"; 
		$destination_path = "jezzy/uploads/company-".$companyId."/config/"; 
		$destination_file = $destination_path.$fileData['name'];
	
		$file = $myFile['tmp_name'];
	
	 $upload = ftp_put($conn_id, $destination_file, $file, FTP_BINARY);// upload the file
    if (!$upload) {// check upload status
        return "UPLOAD_ERROR";
    } else {
        return "http://192.168.1.200/".$destination_file;
    }
}

public function createCompanyDir($companyId){

	// set up basic connection 
		$conn_id = ftp_connect($this->ftpAccentialUploadConfig['host']); 

		// login with username and password 
		$login_result = ftp_login($conn_id, $this->ftpAccentialUploadConfig['user'], $this->ftpAccentialUploadConfig['password']);   
		
		$dir = "/jezzy/uploads/company-".$companyId;
		$dirConfig = "/jezzy/uploads/company-".$companyId."/config";
		$dirOffer = "/jezzy/uploads/company-".$companyId."/offers";
		$dirServices = "/jezzy/uploads/company-".$companyId."/services";
		ftp_mkdir($conn_id, $dir);
		ftp_mkdir($conn_id, $dirConfig);
		ftp_mkdir($conn_id, $dirOffer);
		ftp_mkdir($conn_id, $dirServices);
			

			}
    
    /**
     * Generate Secure TOKEN for API comunication
     */
    public function generateSecureToken() {
        $timestamp = time();
        $array = array('secureNumbers' => $timestamp);
        $json = json_encode($array);
        return base64_encode($json);
    }

    /**
     * Decode the returned data from API
     */
    public function decodeData($base64String = null) {
        if (!$base64String) {
            return null;
        }
        $json = base64_decode($base64String);
        $array = json_decode($json, true);
        return $array;
    }
    



}
