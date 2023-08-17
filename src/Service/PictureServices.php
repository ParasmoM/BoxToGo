<?php 

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureServices
{
    private $parameterBagInterface; 

    public function __construct(ParameterBagInterface $parameterBagInterface)
    {
        $this->parameterBagInterface = $parameterBagInterface;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        
        // On donne un nouveau nom à l'image
        $fichier = md5(uniqid(rand(), true)) . '.webp';

        // On récupère les infos de l'image
        $picture_infos = getimagesize($picture);
        
        if ($picture_infos === false) {
            throw new Exception("Format d'image incorrect");
        }

        // On vérifier le format de l'image
        switch ($picture_infos['mime']) {
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture);
                break;
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            case 'image/webp':
                $picture_source = imagecreatefromwebp($picture);
                break;
            case 'image/avif':
                $picture_source = imagecreatefromavif($picture);
                break;
            default:
                throw new Exception("Format d'image incorrect");
        }

        $path = $this->parameterBagInterface->get('image_directory') . $folder;
        
        // On crée le dossier de destination s'il n'existe pas
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $picture->move($path . '/', $fichier);

        return $fichier;
    }

    public function delete(string $fichier, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if ($fichier !== 'default.webp') {
            $success = false;
            $path = $this->parameterBagInterface->get('image_directory') . $folder;

            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $fichier;

            if (file_exists($mini)) {
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $fichier;

            if (file_exists($original)) {
                unlink($mini);
                $success = true;
            }

            return $success;
        }  

        return false;
    }
}