<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Support\Facades\Config;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => Config::get('cloudinary.cloud_name'),
                'api_key' => Config::get('cloudinary.api_key'),
                'api_secret' => Config::get('cloudinary.api_secret'),
                'url' => Config::get('cloudinary.url'),
            ],
        ]);
    }

    public function upload($file, $folder = 'recolectores')
    {
        try {
            $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => $folder,
                'resource_type' => 'auto',
            ]);

            return [
                'success' => true,
                'url' => $result['secure_url'],
                'public_id' => $result['public_id']
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function delete($publicId)
    {
        try {
            $this->cloudinary->uploadApi()->destroy($publicId);
            return [
                'success' => true
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
} 