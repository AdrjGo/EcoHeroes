<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    protected $apiKey;
    protected $baseUrl = 'https://maps.googleapis.com/maps/api/geocode/json';

    public function __construct()
    {
        $this->apiKey = config('services.google.maps_api_key');
    }

    public function getCoordinates($address)
    {
        try {
            $response = Http::get($this->baseUrl, [
                'address' => $address,
                'key' => $this->apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'OK' && !empty($data['results'])) {
                    $location = $data['results'][0]['geometry']['location'];
                    return [
                        'latitud' => $location['lat'],
                        'longitud' => $location['lng'],
                        'direccion_formateada' => $data['results'][0]['formatted_address']
                    ];
                }
            }

            Log::error('Error en la geocodificación', [
                'address' => $address,
                'response' => $response->json()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Excepción en la geocodificación', [
                'address' => $address,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function getAddress($lat, $lng)
    {
        try {
            $response = Http::get($this->baseUrl, [
                'latlng' => "{$lat},{$lng}",
                'key' => $this->apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'OK' && !empty($data['results'])) {
                    return [
                        'direccion' => $data['results'][0]['formatted_address'],
                        'componentes' => $data['results'][0]['address_components']
                    ];
                }
            }

            Log::error('Error en la geocodificación inversa', [
                'lat' => $lat,
                'lng' => $lng,
                'response' => $response->json()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Excepción en la geocodificación inversa', [
                'lat' => $lat,
                'lng' => $lng,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
} 