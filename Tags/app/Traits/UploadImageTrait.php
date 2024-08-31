<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

trait UploadImageTrait {
    public function uploadImage(Request $request, $field_name = 'image', $folder = 'images') : ?string {
        if ($request->hasFile($field_name) && $request->file($field_name)->isValid()) {
            $image = $request->file($field_name);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $extension = $image->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                throw new \Exception('Invalid file extension. Allowed extensions: jpg, jpeg, png, gif');
            }

            $imageName = time() . '_' . uniqid() . '.' . $extension;

            return $image->storeAs($folder, $imageName, 'public');
        }

        return null;
    }

}
