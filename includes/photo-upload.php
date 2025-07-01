<?php
class PhotoUpload {
    private $uploadDir = 'uploads/photos/';
    private $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    private $maxFileSize = 5 * 1024 * 1024; // 5MB
    private $imageQuality = 85;
    
    public function __construct() {
        // Create upload directory if it doesn't exist
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    public function uploadPhoto($file, $studentId = null) {
        try {
            // Validate file
            $validation = $this->validateFile($file);
            if (!$validation['success']) {
                return $validation;
            }
            
            // Generate unique filename
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $fileName = $this->generateFileName($studentId, $fileExtension);
            $filePath = $this->uploadDir . $fileName;
            
            // Process and optimize image
            $processResult = $this->processImage($file['tmp_name'], $filePath, $fileExtension);
            if (!$processResult['success']) {
                return $processResult;
            }
            
            return [
                'success' => true,
                'message' => 'Foto berhasil diupload',
                'filename' => $fileName,
                'path' => $filePath,
                'url' => $this->getPhotoUrl($fileName)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    private function validateFile($file) {
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return ['success' => false, 'message' => 'Tidak ada file yang dipilih'];
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Error saat upload file'];
        }
        
        // Check file size
        if ($file['size'] > $this->maxFileSize) {
            return ['success' => false, 'message' => 'Ukuran file terlalu besar (maksimal 5MB)'];
        }
        
        // Check file type
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $this->allowedTypes)) {
            return ['success' => false, 'message' => 'Format file tidak didukung (hanya JPG, PNG, GIF)'];
        }
        
        // Verify it's actually an image
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return ['success' => false, 'message' => 'File bukan gambar yang valid'];
        }
        
        return ['success' => true];
    }
    
    private function processImage($sourcePath, $destinationPath, $extension) {
        try {
            // Create image resource based on type
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $sourceImage = imagecreatefromjpeg($sourcePath);
                    break;
                case 'png':
                    $sourceImage = imagecreatefrompng($sourcePath);
                    break;
                case 'gif':
                    $sourceImage = imagecreatefromgif($sourcePath);
                    break;
                default:
                    return ['success' => false, 'message' => 'Format tidak didukung'];
            }
            
            if (!$sourceImage) {
                return ['success' => false, 'message' => 'Gagal memproses gambar'];
            }
            
            // Get original dimensions
            $originalWidth = imagesx($sourceImage);
            $originalHeight = imagesy($sourceImage);
            
            // Calculate new dimensions (max 800px width/height)
            $maxDimension = 800;
            if ($originalWidth > $maxDimension || $originalHeight > $maxDimension) {
                if ($originalWidth > $originalHeight) {
                    $newWidth = $maxDimension;
                    $newHeight = ($originalHeight * $maxDimension) / $originalWidth;
                } else {
                    $newHeight = $maxDimension;
                    $newWidth = ($originalWidth * $maxDimension) / $originalHeight;
                }
            } else {
                $newWidth = $originalWidth;
                $newHeight = $originalHeight;
            }
            
            // Create new image with calculated dimensions
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG and GIF
            if ($extension === 'png' || $extension === 'gif') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefill($newImage, 0, 0, $transparent);
            }
            
            // Resize image
            imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            
            // Save optimized image
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $result = imagejpeg($newImage, $destinationPath, $this->imageQuality);
                    break;
                case 'png':
                    $result = imagepng($newImage, $destinationPath, 9);
                    break;
                case 'gif':
                    $result = imagegif($newImage, $destinationPath);
                    break;
            }
            
            // Clean up memory
            imagedestroy($sourceImage);
            imagedestroy($newImage);
            
            if (!$result) {
                return ['success' => false, 'message' => 'Gagal menyimpan gambar'];
            }
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error memproses gambar: ' . $e->getMessage()];
        }
    }
    
    private function generateFileName($studentId, $extension) {
        $timestamp = time();
        $random = mt_rand(1000, 9999);
        
        if ($studentId) {
            return "student_{$studentId}_{$timestamp}_{$random}.{$extension}";
        } else {
            return "photo_{$timestamp}_{$random}.{$extension}";
        }
    }
    
    public function getPhotoUrl($filename) {
        return $this->uploadDir . $filename;
    }
    
    public function deletePhoto($filename) {
        if (empty($filename)) return true;
        
        $filePath = $this->uploadDir . $filename;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return true;
    }
    
    public function getPhotoInfo($filename) {
        if (empty($filename)) return null;
        
        $filePath = $this->uploadDir . $filename;
        if (file_exists($filePath)) {
            return [
                'exists' => true,
                'size' => filesize($filePath),
                'url' => $this->getPhotoUrl($filename),
                'modified' => filemtime($filePath)
            ];
        }
        return ['exists' => false];
    }
}
?>
