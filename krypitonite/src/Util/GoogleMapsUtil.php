<?php
namespace Krypitonite\Util;

class GoogleMapsUtil
{

    public static function Get_Address_From_Google_Maps($lat, $lon)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=false&key=AIzaSyBRve-6AfKDuUhoY7E7DYEBVCfAb3x-zU8";

        // Make the HTTP request
        $data = @file_get_contents($url);
        // Parse the json response
        $jsondata = json_decode($data, true);

        if ($jsondata["status"] == "OK") {
            $address = array(
                'pais' => self::Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"]),
                'uf' => self::Find_Long_Name_Given_Type("administrative_area_level_1", $jsondata["results"][0]["address_components"], true),
                'cidade' => self::Find_Long_Name_Given_Type("administrative_area_level_2", $jsondata["results"][0]["address_components"]),
                'bairro' => self::Find_Long_Name_Given_Type("sublocality_level_1", $jsondata["results"][0]["address_components"], true),
                'endereco' => self::Find_Long_Name_Given_Type("street_number", $jsondata["results"][0]["address_components"]) . ' ' . self::Find_Long_Name_Given_Type("route", $jsondata["results"][0]["address_components"]),
                'cep' => self::Find_Long_Name_Given_Type("postal_code", $jsondata["results"][0]["address_components"]),
                'formatted_address' => $jsondata["results"][0]["formatted_address"]
            );

            $numero = preg_replace("/[^0-9]/", "", self::Find_Long_Name_Given_Type("sublocality_level_1", $jsondata["results"][0]["address_components"], true));
            if ($numero == NULL || $numero == 0) {
                $numero = preg_replace("/[^0-9]/", "", self::Find_Long_Name_Given_Type("street_number", $jsondata["results"][0]["address_components"]));
            }

            $address['numero'] = $numero;
            return $address;
        }
    }

    public static function Find_Long_Name_Given_Type($type, $array, $short_name = false)
    {
        foreach ($array as $value) {
            if (in_array($type, $value["types"])) {
                if ($short_name)
                    return $value["short_name"];
                return $value["long_name"];
            }
        }
    }
}