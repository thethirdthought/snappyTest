<?php


namespace App\Models;

use CodeIgniter\Model;

class PropertiesModel extends Model
{
    protected string $table = 'properties';
    protected string $primaryKey = 'id';

    protected array $allowedFields = [
        'uuid',
        'property_type_id',
        'county',
        'country',
        'town',
        'description',
        'address',
        'image_full',
        'image_thumbnail',
        'latitude',
        'longitude',
        'num_bedrooms',
        'num_bathrooms',
        'price',
        'type',
        'created_at',
        'updated_at',
        'postal_code',
        'created_by'
    ];

//    protected $validationRules = [
//        'property_type_id' => 'required',
//        'county' => 'required|max_length[45]',
//        'country' => 'required|max_length[45]',
//        'town' => 'required|max_length[45]',
//        'description' => 'required|',
//        'address' => 'required|max_length[255]',
//        'num_bedrooms' => 'required|max_length[45]',
//        'num_bathrooms' => 'required|max_length[45]',
//        'price' => 'required',
//        'type' => 'required'
//    ];

    public function createDataTableQuery($data)
    {
        $sql = "SELECT p.id,pt.title,p.type,p.address,p.num_bedrooms,p.num_bathrooms,p.price,p.image_thumbnail FROM properties p";
        $sql .= " INNER JOIN property_types pt on p.property_type_id = pt.id";
        if ($data['searchKey'] !== "") {
            $sql .= " WHERE p.address like '%" . $data['searchKey'] . "%'";
        }
        $sql .= " ORDER BY " . $data['orderBy'] . " " . $data['orderDir'];
        $sql .= " LIMIT " . $data['start'] . ", " . $data['length'];
//        echo $sql;
        $query = $this->db->query($sql);
        $result = [];
        foreach ($query->getResultArray() as $row) {
            $tempArray = [];
            $tempArray["property_type"] = $row['title'];
            $tempArray["type"] = $row['type'];
            $tempArray["address"] = $row['address'];
            $tempArray["num_bedrooms"] = $row['num_bedrooms'];
            $tempArray["num_bathrooms"] = $row['num_bathrooms'];
            $tempArray["price"] = $row['price'];
            $tempArray["DT_RowId"] = "property_" . $row['id'];
            $result[] = $tempArray;
        }

        return $result;
    }

    public function getPropertyDetails($id)
    {
//        echo $id;
        $builder = $this->db->table($this->table . " as p");
        $builder->select("p.*,pt.title as property_type,pt.description as property_type_description");
        $builder->join("property_types as pt", "p.property_type_id = pt.id");
        $builder->where("p.id", $id);
        $data = $builder->get()->getResult('array');
        $data = $data[0] ?? [];
//        print_r($data);exit;
        return $data;
    }

}