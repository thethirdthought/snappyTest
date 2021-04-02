<?php


namespace App\Helpers;

use App\Models\PropertiesModel;
use App\Models\PropertyTypesModel;
use CodeIgniter\Database\BaseConnection;
//use Config\Database;
use GuzzleHttp\Client;


class ApiHelper
{
    private static string $apiBaseUrl = "https://trial.craig.mtcserver15.com/";

    private array $propertyTypeIds = [];

    private array $propertiesUuids = [];

    private PropertyTypesModel $propertyTypeModel;

    private PropertiesModel $propertiesModel;

    private $db;

    /**
     * ApiHelper constructor.
     */
    public function __construct()
    {
        $this->propertyTypeModel = new PropertyTypesModel();
        $this->propertiesModel = new PropertiesModel();
        // this will store all the existing Property Types for quick lookup
        $this->propertyTypeIds = $this->propertyTypeModel->findColumn("id") ?? [];
        // this will store all the existing Property for quick lookup
        $this->propertiesUuids = $this->propertiesModel->findColumn("uuid") ?? [];
        $this->db = db_connect();
//        exit('hi');
    }


    /**
     * @return array
     */
    public function fetchData(): array
    {
        $response = [];
        $response["dataFetchStartedAt"] = date("Y-m-d H:i:s");
        try {
            $i = 1;
            $url = self::$apiBaseUrl . "api/properties?api_key=".$_ENV["API_KEY"]."&page[size]=30&page[number]=$i";

            while ($url !== null && ++$i < 10) {
                echo $url."\n";
                $apiResponse = $this->getDataFromApi($url);
                if (!$apiResponse['success']) {
                    throw new \Exception($apiResponse["error"]);
                }
                $body = $apiResponse["data"] ?? null;

                if (!$body) {
                    throw new \Exception("No data from API");
                }
                $body = json_decode($body,true);

                //Tried using next url from api response but api is not consistent and keeps on failing.
//                $url = $body["next_page_url"] ?? null;
                if($body["last_page"] >= $i) {
                    $url = self::$apiBaseUrl . "api/properties?api_key=".$_ENV["API_KEY"]."&page[size]=30&page[number]=$i";
                }
                $data = $body["data"] ?? null;
                echo json_encode($body) . "\n\n\n\n\n\n";
                if (!$data || !count($data)) {
                    continue;
                }

                $this->fillData($data);

            }
            $response["success"] = true;
            $response["dataFetchCompletedAt"] = date("Y-m-d H:i:s");
        } catch (\Exception $e) {
            $response["success"] = false;
            $response["error"] = $e->getMessage();
        }

        return $response;

    }

    /**
     * @param array $data
     */
    private function fillData(array $data): void
    {
        $propertiesToUpdate = [];
        $propertiesToInsert = [];
        $propertyTypesToUpdate = [];
        $propertyTypesToInsert = [];
        $propertyTypeIncluded = [];

        foreach ($data as $property) {
            $propertyUuid = $property["uuid"];
            $propertyTypeId = $property["property_type_id"];
            if (!in_array($propertyTypeId, $propertyTypeIncluded) && in_array($propertyTypeId, $this->propertyTypeIds)) {
                $propertyTypesToUpdate[] = $property['property_type'];
                $propertyTypeIncluded[] = $propertyTypeId;
            } else if (!in_array($propertyTypeId, $propertyTypeIncluded) && !in_array($propertyTypeId, $this->propertyTypeIds)) {
                $propertyTypesToInsert[] = $property['property_type'];
                $propertyTypeIncluded[] = $propertyTypeId;
            }

            if (!in_array($propertyUuid, $this->propertiesUuids)) {
                unset($property['property_type']);
                $propertiesToInsert[] = $property;
            } else {
                unset($property['property_type']);
                $propertiesToUpdate[] = $property;
            }
        }

        $propertyTypeBuilder = $this->db->table('property_types');

        $propertyTypeBuilder->insertBatch($propertyTypesToInsert);

        $propertyTypeBuilder->updateBatch($propertyTypesToUpdate, "id");

        $propertiesBuilder = $this->db->table('properties');

        $propertiesBuilder->insertBatch($propertiesToInsert);
        $propertiesBuilder->updateBatch($propertiesToInsert, "uuid");

        return;
    }

    /**
     * @param String $url
     * @param array $request
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getDataFromApi(String $url, array $request = []): array
    {
        $result = [];
        try {
            $client = new Client([
                'timeout' => 2.0,
            ]);
            $response = $client->request('GET',
                $url,
                ['query' => $request]
            );

            $responseCode = $response->getStatusCode();
            if ($responseCode > 400) {
                throw new \Exception("Api responded with error code $responseCode");
            }

            $data = $response->getBody()->getContents();
            $result["success"] = true;
            $result["data"] = $data;

        } catch (\Exception $e) {
            $result["success"] = false;
            $result["error"] = $e->getMessage();
        }
        return $result;

    }
}