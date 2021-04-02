<?php

namespace App\Controllers;

use App\Helpers\ApiHelper;
use App\Helpers\ImageHelper;
use App\Models\PropertiesModel;
use App\Models\PropertyTypesModel;

class Home extends BaseController
{

    // used for datatable.
    Const COLUMNS = [
        "property_type_id",
        "type",
        "address",
        "num_bedrooms",
        "num_bathrooms",
        "price",
        "image_thumbnail"
    ];

    public function index()
    {
        $data['maxBedroom'] = 30;
        $data['maxBathroom'] = 30;
        $propertyTypeModal = new PropertyTypesModel();
        $data['propertyTypes'] = $propertyTypeModal->findAll();
        echo view('template/header');
        echo view('dashboard', $data);
        echo view('template/footer');
    }

    public function fetchData()
    {
        $apiHelper = new ApiHelper();
        $response = $apiHelper->fetchData();
        return json_encode($response);
    }

    public function addProperty()
    {
        $request = service('request');
        $response = [];
        $requestData = [];
        $requestData['county'] = $request->getPost('county');
        $requestData['country'] = $request->getPost('country');
        $requestData['town'] = $request->getPost('town');
        $requestData['postal_code'] = $request->getPost('postal_code');
        $requestData['description'] = $request->getPost('description');
        $requestData['address'] = $request->getPost('address');
        $requestData['num_bedrooms'] = $request->getPost('num_bedrooms');
        $requestData['num_bathrooms'] = $request->getPost('num_bathrooms');
        $requestData['price'] = $request->getPost('price');
        $requestData['property_type_id'] = $request->getPost('property_type_id');
        $requestData['type'] = $request->getPost('type');
        try {
            $fullImageUploadResponse = ImageHelper::saveFullImage($_FILES);
            if (!$fullImageUploadResponse['success']) {
                throw new \Exception($fullImageUploadResponse["error"]);
            }
            $thumbnailPath = str_replace("fullImage", "thumbnail", $fullImageUploadResponse["location"]);
            $generateThumbnail = ImageHelper::createThumbnail($fullImageUploadResponse["location"],
                $thumbnailPath, 100);

            $requestData["image_full"] = $fullImageUploadResponse["relativePath"];
            $requestData["image_thumbnail"] = str_replace("fullImage", "thumbnail", $fullImageUploadResponse["relativePath"]);
            $requestData['uuid'] = $this->guidv4();
            $requestData['created_by'] = 2;
            $propertyModel = new PropertiesModel();
            $addProperty = $propertyModel->insert($requestData);
            $response["success"] = true;
//            $response["error"] = $e->getMessage();
        } catch (\Exception $e) {
            $response["success"] = false;
            $response["error"] = $e->getMessage();
        }
        return json_encode($response);
    }


    public function editProperty()
    {
        $request = service('request');
        $response = [];
        $requestData = [];
        $requestData['county'] = $request->getPost('county');
        $requestData['country'] = $request->getPost('country');
        $requestData['town'] = $request->getPost('town');
        $requestData['postal_code'] = $request->getPost('postal_code');
        $requestData['description'] = $request->getPost('description');
        $requestData['address'] = $request->getPost('address');
        $requestData['num_bedrooms'] = $request->getPost('num_bedrooms');
        $requestData['num_bathrooms'] = $request->getPost('num_bathrooms');
        $requestData['price'] = $request->getPost('price');
        $requestData['property_type_id'] = $request->getPost('property_type_id');
        $requestData['type'] = $request->getPost('type');
        $id = $request->getPost('id');

        try {
            if (isset($_FILES['image_full']['name']) && $_FILES['image_full']['name'] != "") {
                $fullImageUploadResponse = ImageHelper::saveFullImage($_FILES);
                if (!$fullImageUploadResponse['success']) {
                    throw new \Exception($fullImageUploadResponse["error"]);
                }
                $thumbnailPath = str_replace("fullImage", "thumbnail", $fullImageUploadResponse["location"]);
                $generateThumbnail = ImageHelper::createThumbnail($fullImageUploadResponse["location"],
                    $thumbnailPath, 100);

                $requestData["image_full"] = $fullImageUploadResponse["relativePath"];
                $requestData["image_thumbnail"] = str_replace("fullImage", "thumbnail", $fullImageUploadResponse["relativePath"]);
            }

//            $requestData['uuid'] = $this->guidv4();
            $propertyModel = new PropertiesModel();
            $addProperty = $propertyModel->update($id, $requestData);
            $response["success"] = true;
        } catch (\Exception $e) {
            $response["success"] = false;
            $response["error"] = $e->getMessage();
        }
        return json_encode($response);
    }

    public function getProperties()
    {
        $request = service('request');
        $response = [];
        $requestData = [];
//        echo json_encode($_GET);exit;

        $requestData['start'] = $request->getGet('start');
        $requestData['length'] = $request->getGet('length');
        $requestData['searchKey'] = $request->getGet('search[value]');
        $requestData['orderBy'] = self::COLUMNS[$request->getGet('order[0][column]')];
        $requestData['orderDir'] = $request->getGet('order[0][dir]');
        $propertyModel = new PropertiesModel();
        $response = $_GET;
        $response["data"] = $propertyModel->createDataTableQuery($requestData);
        return $this->response->setJSON($response);
//        return json_encode($response);

    }

    public function detailPage()
    {
        $request = service('request');
        $property = $request->getGet('id');
        if (!$property) {
            return view('template/header');
        }
        $property = explode("_", $property);

        $propertyModel = new PropertiesModel();
        $data['maxBedroom'] = 30;
        $data['maxBathroom'] = 30;
        $propertyTypeModal = new PropertyTypesModel();
        $data['propertyTypes'] = $propertyTypeModal->findAll();
        $data["propertyData"] = $propertyModel->getPropertyDetails($property[1]);
//        echo json_encode($data);exit;
        echo view('template/header');
        echo view('property_details', $data);
        echo view('template/footer');
    }

    private function guidv4($data = null)
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


}
