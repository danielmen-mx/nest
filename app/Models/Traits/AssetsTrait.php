<?php

namespace App\Models\Traits;

use App\Models\Cupboard\Post;

trait AssetsTrait
{
    private function validateRequest($request)
    {
        if (!$request->hasFile("image")) {
            throw new Exception("The request doesn't have any asset.");
        }

        return $request->file("image");
    }

    /**
     * Method processAsset
     * Save the asset to our public folder, use the owner to keep control of the assets
     * 
     * @param Post $post is the owner of the asset that we are going to store
     * @return void
     */
    public function processAsset(Post $post, $request): void
    {
        $file = $this->validateRequest($request);
        $assetName = $this->nameAsset($file);
        $location = $this->getLocation($post->getAssetIdentifier());

        $this->storeAsset($file, $location, $assetName);
    }

    private function getLocation($identifier)
    {
        return public_path("images") . "/" . $identifier;
    }

    /**
     * Method getAssetPath
     * Returns the location of the file
     * 
     * @param Post $post is the record of the owner of the asset
     * @param $file is the file sent in the client request
     * @return String
     */
    public function getAssetStorePath(Post $post, $request): String
    {
        $file = $this->validateRequest($request);
        $assetName = $this->nameAsset($file);
        return $this->getLocalPort() . $this->getPersonalAssetFolder($post) . $assetName;
    }

    /**
     * Method nameAsset
     * Defines the name of the asset that is about to be stored
     * 
     * @param $file is the asset sent by the client request
     * @return String
     */
    private function nameAsset($file): String
    {           
        $assetName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        if (str_contains($assetName, ".")) {
            $fragments = explode(".", $assetName);
            array_pop($fragments);

            $assetName = implode($fragments);
        }

        return $assetName . "." . $extension;
    }

    private function storeAsset($file, $location, $assetName): void
    {
        $file->move($location, $assetName);
    }

    private function getLocalPort()
    {
        return env("LOCAL_PORT");
    }

    private function getPersonalAssetFolder($post)
    {
        return "images/" . $post->getAssetIdentifier() . "/";
    }

    public function getPublicPathFilesLocation($post): String
    {
        return public_path("images") . "/" . $post->getAssetIdentifier();
    }
}